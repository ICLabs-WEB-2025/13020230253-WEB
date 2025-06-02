<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Offer;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerController extends Controller
{
    public function index()
    {
        $houses = House::where('status', 'Tersedia')
            ->with('agent')
            ->whereHas('agent', function ($query) {
                $query->where('is_approved', true);
            })
            ->get();

        // Ambil riwayat pesan jika ada conversationId di session
        $messages = [];
        if (session('conversationId')) {
            $messages = Message::where('conversation_id', session('conversationId'))
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('buyer.index', compact('houses', 'messages'));
    }

    public function show($id)
    {
        $house = House::with('agent')->findOrFail($id);
        if ($house->status !== 'Tersedia') {
            return redirect()->route('buyer.index')->with('error', 'Rumah ini tidak tersedia.');
        }
        return view('buyer.show', compact('house'));
    }

    public function requestPurchase(Request $request, $id)
    {
        $house = House::findOrFail($id);
        if ($house->status !== 'Tersedia') {
            return redirect()->route('buyer.index')->with('error', 'Rumah ini tidak tersedia.');
        }

        if (Auth::check() && Auth::user()->role === 'buyer') {
            $buyerId = Auth::id();
            $agentId = $house->agent_id;

            // Buat atau cari percakapan
            $conversation = Conversation::firstOrCreate(
                [
                    'buyer_id' => $buyerId,
                    'agent_id' => $agentId,
                    'house_id' => $house->id,
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Buat pesan awal
            $messageText = "Saya tertarik dengan {$house->title}. Bisakah kita membahas lebih lanjut?";
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $buyerId,
                'sender_type' => 'buyer',
                'message_text' => $messageText,
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Buat penawaran (mempertahankan logika asli)
            Offer::create([
                'house_id' => $house->id,
                'buyer_id' => $buyerId,
                'offer_price' => $house->price,
                'status' => 'Tertunda',
            ]);

            return redirect()->route('buyer.index')->with([
                'success' => 'Permintaan pembelian untuk ' . $house->title . ' telah dikirim ke agen.',
                'openChat' => true,
                'conversationId' => $conversation->id,
            ]);
        }

        return redirect()->route('login')->with('error', 'Anda harus login sebagai buyer.');
    }

    public function offers(Request $request)
    {
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'offer_price');
        $sortOrder = $request->query('sort_order', 'asc');

        $offers = Offer::where('buyer_id', Auth::id())
            ->when($search, function ($query, $search) {
                $query->whereHas('house', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            })
            ->orderBy($sortBy, $sortOrder)
            ->paginate(10);

        return view('buyer.offers', compact('offers', 'search', 'sortBy', 'sortOrder'));
    }

    public function cancel(Offer $offer)
    {
        if ($offer->buyer_id !== Auth::id()) {
            return redirect()->route('buyer.offers')->with('error', 'Anda tidak memiliki izin untuk membatalkan penawaran ini.');
        }

        if ($offer->status !== 'Tertunda') {
            return redirect()->route('buyer.offers')->with('error', 'Penawaran ini tidak dapat dibatalkan karena sudah diproses.');
        }

        $offer->delete();
        return redirect()->route('buyer.offers')->with('success', 'Penawaran berhasil dibatalkan.');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message_text' => 'required|string|max:1000',
        ]);

        $conversation = Conversation::findOrFail($request->conversation_id);
        if ($conversation->buyer_id !== Auth::id()) {
            return redirect()->route('buyer.index')->with('error', 'Akses ditolak.');
        }

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'sender_type' => 'buyer',
            'message_text' => $request->message_text,
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('buyer.index')->with([
            'success' => 'Pesan terkirim.',
            'openChat' => true,
            'conversationId' => $conversation->id,
        ]);
    }
}