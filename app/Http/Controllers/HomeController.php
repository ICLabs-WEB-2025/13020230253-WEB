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
        if (Auth::check()) {
            $user = Auth::user();
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

        // Ambil 6 rumah tersedia untuk Properti Unggulan
        $featuredHouses = House::where('status', 'Tersedia')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Log untuk debugging
        Log::info('Featured Houses Count: ' . $featuredHouses->count());
        Log::info('Featured Houses Data: ', $featuredHouses->toArray());

        return view('welcome', compact('featuredHouses'));
    }

    public function houses(Request $request)
    {
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'created_at');
        $sortOrder = $request->query('sort_order', 'desc');

        // Ambil rumah dengan paginasi
        $houses = House::where('status', 'Tersedia')
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', '%' . $search . '%');
            })
            ->orderBy($sortBy, $sortOrder)
            ->paginate(9); // Paginasi, 9 item per halaman

        return view('houses.index', compact('houses', 'search', 'sortBy', 'sortOrder'));
    }
}