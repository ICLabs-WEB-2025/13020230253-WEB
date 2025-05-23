<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Offer;
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

        return view('buyer.index', compact('houses'));
    }

    public function show($id)
    {
        $house = House::with('agent')->findOrFail($id);
        if ($house->status !== 'Tersedia') {
            return redirect()->route('buyer.index')->with('error', 'Rumah ini tidak tersedia.');
        }
        return view('buyer.show', compact('house'));
    }

    public function requestPurchase($id)
    {
        $house = House::findOrFail($id);
        if (Auth::check() && Auth::user()->role === 'buyer') {
            // Buat penawaran baru (contoh sederhana)
            Offer::create([
                'house_id' => $house->id,
                'buyer_id' => Auth::id(),
                'offer_price' => $house->price, // Harga awal sama dengan harga rumah
                'status' => 'Tertunda',
            ]);
            return redirect()->route('buyer.index')->with('success', 'Permintaan pembelian untuk ' . $house->title . ' telah dikirim ke agen.');
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
}