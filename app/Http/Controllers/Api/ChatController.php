<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User; // Pastikan Anda memiliki model User
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Mengambil daftar percakapan untuk agen yang sedang login.
     * Termasuk pesan terakhir dan jumlah pesan yang belum dibaca.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAgentConversations(Request $request)
    {
        $agent = Auth::user();

        if (!$agent || $agent->role !== 'agent') {
            return response()->json(['message' => 'Unauthorized. Only agents can view conversations.'], 401);
        }

        $conversations = Conversation::where('agent_id', $agent->id)
            ->with(['buyer', 'messages' => function($query) {
                $query->orderBy('created_at', 'desc')->take(1); // Ambil pesan terakhir
            }])
            ->orderByDesc('updated_at') // Urutkan percakapan berdasarkan yang terbaru di-update
            ->get();

        $formattedConversations = $conversations->map(function ($conversation) use ($agent) {
            // Hitung pesan yang belum dibaca oleh agen
            $unreadCount = $conversation->messages()
                                         ->where('sender_type', 'buyer') // Pesan dari pembeli
                                         ->where('is_read', false) // Yang belum dibaca
                                         ->count();

            return [
                'id' => $conversation->id,
                'buyer_name' => $conversation->buyer->name ?? 'Pembeli Tak Dikenal',
                'last_message' => $conversation->messages->first(), // Ambil pesan pertama dari collection yang sudah di-take(1)
                'unread_count' => $unreadCount,
                // Tambahkan detail lain yang mungkin Anda butuhkan, misal house_title jika chat per properti
                // 'house_title' => $conversation->house->title ?? null,
            ];
        });

        return response()->json(['conversations' => $formattedConversations], 200);
    }

    /**
     * Mengambil riwayat pesan untuk percakapan tertentu.
     * Memfilter pesan yang lebih baru dari last_message_id yang diberikan oleh frontend.
     * Juga menandai pesan sebagai sudah dibaca oleh agen.
     * @param Request $request
     * @param int $conversationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessages(Request $request, $conversationId)
    {
        $user = Auth::user(); // Bisa agent atau buyer

        if (!$user) {
            return response()->json(['message' => 'Unauthorized. User not authenticated.'], 401);
        }

        $conversation = Conversation::find($conversationId);

        if (!$conversation) {
            return response()->json(['message' => 'Conversation not found.'], 404);
        }

        // Pastikan user adalah bagian dari percakapan (agen atau pembeli)
        if (! (($user->role === 'buyer' && $conversation->buyer_id === $user->id) ||
               ($user->role === 'agent' && $conversation->agent_id === $user->id)) ) {
            return response()->json(['message' => 'Forbidden. Not authorized to view this conversation.'], 403);
        }

        $query = $conversation->messages()->orderBy('created_at', 'asc');

        // Filter pesan yang lebih baru dari last_message_id jika disediakan
        $lastMessageId = $request->query('last_message_id', 0);
        if ($lastMessageId > 0) {
            $query->where('id', '>', $lastMessageId);
        }
        
        $messages = $query->get();

        // Tandai pesan dari lawan bicara sebagai sudah dibaca
        if ($user->role === 'agent') {
            $conversation->messages()->where('sender_type', 'buyer')->where('is_read', false)->update(['is_read' => true]);
        } elseif ($user->role === 'buyer') {
            $conversation->messages()->where('sender_type', 'agent')->where('is_read', false)->update(['is_read' => true]);
        }
        // Pastikan updated_at conversation juga ter-update saat pesan dibaca
        $conversation->touch();


        $formattedMessages = $messages->map(function ($message) use ($user) {
            // Determine if the message was sent by the current authenticated user
            $isSentByMe = ($message->sender_id === $user->id && $message->sender_type === $user->role);
            
            // Get sender name
            $senderName = '';
            if ($message->sender_type === 'buyer') {
                $sender = User::find($message->sender_id);
                $senderName = $sender->name ?? 'Pembeli';
            } elseif ($message->sender_type === 'agent') {
                $sender = User::find($message->sender_id);
                $senderName = $sender->name ?? 'Agen';
            }


            return [
                'id' => $message->id,
                'conversation_id' => $message->conversation_id,
                'sender_id' => $message->sender_id,
                'sender_type' => $message->sender_type,
                'sender_name' => $senderName, // Include sender name
                'message_text' => $message->message_text,
                'is_read' => $message->is_read,
                'created_at' => $message->created_at->toDateTimeString(),
                'is_sent_by_me' => $isSentByMe,
            ];
        });

        return response()->json([
            'conversation_id' => $conversation->id,
            'messages' => $formattedMessages,
        ], 200);
    }

    /**
     * Mengirim pesan dari agen ke pembeli dalam percakapan yang sudah ada.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessageAgent(Request $request)
    {
        $request->validate([
            'message_text' => 'required|string|max:1000',
            'conversation_id' => 'required|exists:conversations,id',
        ]);

        $agent = Auth::user();

        if (!$agent || $agent->role !== 'agent') {
            return response()->json(['message' => 'Unauthorized. Only agents can send messages.'], 401);
        }

        $conversation = Conversation::find($request->input('conversation_id'));

        if (!$conversation || $conversation->agent_id !== $agent->id) {
            return response()->json(['message' => 'Conversation not found or not authorized for this agent.'], 403);
        }

        try {
            DB::beginTransaction();

            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $agent->id,
                'sender_type' => 'agent', // Tipe pengirim adalah 'agent'
                'message_text' => $request->input('message_text'),
                'is_read' => false, // Set false untuk pesan yang baru dikirim (oleh agen, belum dibaca pembeli)
            ]);

            // Update timestamp percakapan agar muncul paling atas di daftar
            $conversation->touch();

            DB::commit();

            return response()->json([
                'message' => 'Message sent successfully!',
                'conversation_id' => $conversation->id,
                'sent_message' => [
                    'id' => $message->id,
                    'message_text' => $message->message_text,
                    'sender_type' => $message->sender_type,
                    'sender_name' => $agent->name, // Nama agen pengirim
                    'created_at' => $message->created_at->toDateTimeString(),
                    'is_sent_by_me' => true,
                ],
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Failed to send message from agent: " . $e->getMessage());
            return response()->json(['message' => 'Failed to send message.', 'error' => $e->getMessage()], 500);
        }
    }


    // Fungsi sendMessage (untuk buyer) yang sudah ada, mungkin perlu sedikit penyesuaian jika digunakan bersama.
    // Jika ada di buyer/index.blade.php, pastikan sender_type-nya 'buyer'
    public function sendMessage(Request $request) // Ini mungkin endpoint untuk pembeli
    {
        $request->validate([
            'message_text' => 'required|string|max:1000',
            'conversation_id' => 'nullable|exists:conversations,id',
            // 'house_id' => 'nullable|exists:houses,id', // Jika pesan terkait properti
        ]);

        $user = Auth::user(); // User yang sedang login (pembeli)

        if (!$user || $user->role !== 'buyer') { // Pastikan hanya buyer yang bisa menggunakan ini
            return response()->json(['message' => 'Unauthorized. Only buyers can send messages through this endpoint.'], 401);
        }

        $conversation = null;

        if ($request->filled('conversation_id')) {
            $conversation = Conversation::find($request->input('conversation_id'));
            if (!$conversation || $conversation->buyer_id !== $user->id) { // Pastikan buyer yang benar
                return response()->json(['message' => 'Conversation not found or not authorized.'], 403);
            }
        } else {
            // Jika belum ada conversation_id, cari atau buat percakapan baru dengan agen acak/default.
            // Anda mungkin perlu menambahkan logic untuk memilih agen tertentu (misal: agen properti yang diminati)
            $agent = User::where('role', 'agent')->first(); // Ambil agen pertama sebagai default
            if (!$agent) {
                return response()->json(['message' => 'No agent available to chat with.'], 404);
            }

            $conversation = Conversation::firstOrCreate(
                ['buyer_id' => $user->id, 'agent_id' => $agent->id], // Conditions to find existing
                [] // Attributes to create if not found
            );
        }

        try {
            DB::beginTransaction();

            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'sender_type' => 'buyer', // Tipe pengirim adalah 'buyer'
                'message_text' => $request->input('message_text'),
                'is_read' => false, // Set false untuk pesan yang baru dikirim (oleh pembeli, belum dibaca agen)
            ]);

            $conversation->touch(); // Update timestamp percakapan

            DB::commit();

            return response()->json([
                'message' => 'Message sent successfully!',
                'conversation_id' => $conversation->id,
                'sent_message' => [
                    'id' => $message->id,
                    'message_text' => $message->message_text,
                    'sender_type' => $message->sender_type,
                    'created_at' => $message->created_at->toDateTimeString(),
                    'is_sent_by_me' => true,
                ],
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Failed to send message from buyer: " . $e->getMessage());
            return response()->json(['message' => 'Failed to send message.', 'error' => $e->getMessage()], 500);
        }
    }
}
