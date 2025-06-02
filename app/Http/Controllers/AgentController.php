<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Offer;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AgentController extends Controller
{
    /**
     * Display a listing of the agent's houses and conversations.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'price');
        $sortOrder = $request->query('sort_order', 'asc');

        // Validasi kolom pengurutan untuk mencegah SQL injection
        $validSortColumns = ['price', 'title', 'status', 'created_at'];
        $sortBy = in_array($sortBy, $validSortColumns) ? $sortBy : 'price';
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'asc';

        // Ambil daftar rumah milik agen
        $houses = House::where('agent_id', Auth::id())
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy($sortBy, $sortOrder)
            ->paginate(10);

        // Ambil daftar percakapan untuk agen
        $conversations = Conversation::where('agent_id', Auth::id())
            ->with(['buyer', 'house', 'messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->map(function ($conversation) {
                return (object) [
                    'id' => $conversation->id,
                    'buyer' => (object) ['name' => $conversation->buyer->name],
                    'house' => (object) ['title' => $conversation->house->title],
                    'unread_count' => $conversation->unread_count,
                    'messages' => $conversation->messages->isNotEmpty() ? collect([(object) [
                        'message_text' => $conversation->messages->first()->message_text,
                        'created_at' => $conversation->messages->first()->created_at,
                    ]]) : collect([]),
                ];
            });

        return view('agent.index', compact('houses', 'search', 'sortBy', 'sortOrder', 'conversations'));
    }

    /**
     * Show the form for creating a new house.
     */
    public function create()
    {
        return view('agent.create');
    }

    /**
     * Store a newly created house in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $directory = 'houses';
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
                Log::info('Created directory: ' . $directory . ' on public disk');
            }

            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs($directory, $fileName, 'public');

            if (!$path || !Storage::disk('public')->exists($path)) {
                throw new \Exception('Gagal menyimpan file gambar. Path: ' . $path);
            }

            Log::info('File uploaded: ' . $fileName . ' to ' . $path);

            $house = House::create([
                'agent_id' => Auth::id(),
                'title' => $validated['title'],
                'price' => $validated['price'],
                'photo_path' => 'public/' . $path,
                'status' => 'Tersedia',
            ]);

            Log::info('House created with ID: ' . $house->id . ' and photo_path: ' . $house->photo_path);

            return redirect()->route('agent.index')->with('success', 'Rumah berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Failed to store house: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengunggah rumah: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified house.
     */
    public function edit(House $house)
    {
        if ($house->agent_id !== Auth::id()) {
            return redirect()->route('agent.index')->with('error', 'Anda tidak memiliki izin untuk mengedit rumah ini.');
        }
        return view('agent.edit', compact('house'));
    }

    /**
     * Update the specified house in storage.
     */
    public function update(Request $request, House $house)
    {
        if ($house->agent_id !== Auth::id()) {
            return redirect()->route('agent.index')->with('error', 'Anda tidak memiliki izin untuk mengedit rumah ini.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:Tersedia,Dalam Proses,Terjual',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            if ($request->hasFile('photo')) {
                if ($house->photo_path && Storage::disk('public')->exists(str_replace('public/', '', $house->photo_path))) {
                    Storage::disk('public')->delete(str_replace('public/', '', $house->photo_path));
                    Log::info('Old photo deleted: ' . $house->photo_path);
                }

                $directory = 'houses';
                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                    Log::info('Created directory: ' . $directory . ' on public disk');
                }

                $file = $request->file('photo');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs($directory, $fileName, 'public');

                if (!$path || !Storage::disk('public')->exists($path)) {
                    throw new \Exception('Gagal menyimpan file gambar baru. Path: ' . $path);
                }

                $validated['photo_path'] = 'public/' . $path;
                Log::info('New photo uploaded: ' . $fileName . ' to ' . $path);
            }

            $house->update($validated);
            Log::info('House updated with ID: ' . $house->id);

            return redirect()->route('agent.index')->with('success', 'Rumah berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Failed to update house: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui rumah: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified house from storage.
     */
    public function destroy(House $house)
    {
        if ($house->agent_id !== Auth::id()) {
            return redirect()->route('agent.index')->with('error', 'Anda tidak memiliki izin untuk menghapus rumah ini.');
        }

        try {
            if ($house->photo_path && Storage::disk('public')->exists(str_replace('public/', '', $house->photo_path))) {
                Storage::disk('public')->delete(str_replace('public/', '', $house->photo_path));
                Log::info('Photo deleted: ' . $house->photo_path);
            }
            $house->delete();
            Log::info('House deleted with ID: ' . $house->id);

            return redirect()->route('agent.index')->with('success', 'Rumah berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Failed to delete house: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus rumah: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of offers for the agent's houses.
     */
    public function requests(Request $request)
    {
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'offer_price');
        $sortOrder = $request->query('sort_order', 'asc');

        // Validasi kolom pengurutan
        $validSortColumns = ['offer_price', 'created_at', 'status'];
        $sortBy = in_array($sortBy, $validSortColumns) ? $sortBy : 'offer_price';
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'asc';

        $offers = Offer::whereHas('house', function ($query) {
            $query->where('agent_id', Auth::id());
        })
        ->when($search, function ($query, $search) {
            $query->whereHas('house', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        })
        ->orderBy($sortBy, $sortOrder)
        ->paginate(10);

        return view('agent.requests', compact('offers', 'search', 'sortBy', 'sortOrder'));
    }

    /**
     * Approve an offer and mark the house as sold.
     */
    public function approveOffer(Offer $offer)
    {
        if ($offer->house->agent_id !== Auth::id()) {
            return redirect()->route('agent.requests')->with('error', 'Anda tidak memiliki izin untuk menyetujui penawaran ini.');
        }

        try {
            $offer->update(['status' => 'Disetujui']);
            $offer->house->update(['status' => 'Terjual']);
            Log::info('Offer approved for house ID: ' . $offer->house_id);

            return redirect()->route('agent.requests')->with('success', 'Penawaran disetujui.');
        } catch (\Exception $e) {
            Log::error('Failed to approve offer: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyetujui penawaran: ' . $e->getMessage());
        }
    }

    /**
     * Reject an offer.
     */
    public function rejectOffer(Offer $offer)
    {
        if ($offer->house->agent_id !== Auth::id()) {
            return redirect()->route('agent.requests')->with('error', 'Anda tidak memiliki izin untuk menolak penawaran ini.');
        }

        try {
            $offer->update(['status' => 'Ditolak']);
            Log::info('Offer rejected for house ID: ' . $offer->house_id);

            return redirect()->route('agent.requests')->with('success', 'Penawaran ditolak.');
        } catch (\Exception $e) {
            Log::error('Failed to reject offer: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menolak penawaran: ' . $e->getMessage());
        }
    }

    /**
     * Get conversations for the agent (for AJAX polling).
     */
    public function getConversations(Request $request)
    {
        $conversations = Conversation::where('agent_id', Auth::id())
            ->with(['buyer', 'house', 'messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->map(function ($conversation) {
                return [
                    'id' => $conversation->id,
                    'buyer_name' => $conversation->buyer->name,
                    'house_title' => $conversation->house->title,
                    'unread_count' => $conversation->unread_count,
                    'last_message' => $conversation->messages->first() ? [
                        'message_text' => $conversation->messages->first()->message_text,
                        'created_at' => $conversation->messages->first()->created_at->toDateTimeString(),
                    ] : null,
                ];
            });

        return response()->json(['conversations' => $conversations]);
    }

    /**
     * Get messages for a specific conversation.
     */
    public function getMessages(Request $request, $conversationId)
    {
        $conversation = Conversation::where('id', $conversationId)
            ->where('agent_id', Auth::id())
            ->firstOrFail();

        $messages = $conversation->messages()
            ->where('id', '>', $request->query('last_message_id', 0))
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'sender_type' => $message->sender_type,
                    'sender_name' => $message->sender_type === 'buyer' ? $message->buyer->name : 'Anda',
                    'message_text' => $message->message_text,
                    'created_at' => $message->created_at->toDateTimeString(),
                ];
            });

        // Reset unread_count untuk agen
        $conversation->update(['unread_count' => 0]);

        return response()->json(['messages' => $messages]);
    }

    /**
     * Send a message in a conversation.
     */
    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'message_text' => 'required|string|max:1000',
        ]);

        $conversation = Conversation::where('id', $conversationId)
            ->where('agent_id', Auth::id())
            ->firstOrFail();

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'sender_type' => 'agent',
            'message_text' => $request->input('message_text'),
        ]);

        // Update unread_count untuk pembeli
        $conversation->update(['unread_count' => \DB::raw('unread_count + 1')]);

        return response()->json([
            'sent_message' => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'sender_type' => $message->sender_type,
                'sender_name' => 'Anda',
                'message_text' => $message->message_text,
                'created_at' => $message->created_at->toDateTimeString(),
            ]
        ]);
    }
}
?>