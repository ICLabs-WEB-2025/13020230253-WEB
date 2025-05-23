<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        // Jika sudah login, arahkan sesuai peran
        if (Auth::check()) {
            $user = Auth::user();
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.agent.applications');
                case 'agent':
                    if ($user->is_approved) {
                        return redirect()->route('agent.index');
                    }
                    return redirect()->route('home')->with('error', 'Akun agen Anda belum disetujui.');
                case 'buyer':
                    return redirect()->route('buyer.index');
                default:
                    return redirect()->route('home')->with('error', 'Role pengguna tidak valid.');
            }
        }
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended('/admin/applications');
                case 'agent':
                    if ($user->is_approved) {
                        return redirect()->intended('/agent/listings');
                    }
                    Auth::logout(); // Logout jika agen belum disetujui
                    return redirect()->route('login')->with('error', 'Akun agen Anda belum disetujui.');
                case 'buyer':
                    return redirect()->intended('/buyer');
                default:
                    return redirect()->route('home')->with('error', 'Role pengguna tidak valid.');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}