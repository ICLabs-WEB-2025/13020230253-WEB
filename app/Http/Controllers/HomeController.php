<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        // Jika pengguna sudah login, arahkan berdasarkan peran
        if (auth()->check()) {
            $user = auth()->user();
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.agent.applications');
                case 'agent':
                    if ($user->is_approved) {
                        return redirect()->route('agent.index');
                    }
                    return view('welcome')->with('error', 'Akun agen Anda belum disetujui.');
                case 'buyer':
                    return redirect()->route('buyer.index');
                default:
                    return redirect()->route('home')->with('error', 'Role pengguna tidak valid.');
            }
        }

        // Ambil 6 rumah tersedia dari semua agen untuk Properti Unggulan
        $featuredHouses = House::where('status', 'Tersedia')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Log untuk debugging
        Log::info('Featured Houses Count: ' . $featuredHouses->count());
        Log::info('Featured Houses Data: ', $featuredHouses->toArray());

        return view('welcome', compact('featuredHouses'));
    }

    public function houses()
    {
        $houses = House::where('status', 'Tersedia')->get();
        return view('houses.index', compact('houses'));
    }
}