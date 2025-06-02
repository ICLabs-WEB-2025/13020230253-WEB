<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Pastikan Anda mengimpor model yang diperlukan untuk relasi chat
use App\Models\House;
use App\Models\Offer;
use App\Models\Message; // Tambahkan ini
use App\Models\Conversation; // Tambahkan ini

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Menggunakan 'role' sebagai pengganti 'user_type'
        'is_approved',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_approved' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Relasi dengan rumah (sebagai agen).
     */
    public function houses()
    {
        return $this->hasMany(House::class, 'agent_id');
    }

    /**
     * Relasi dengan penawaran (sebagai pembeli).
     */
    public function offers()
    {
        return $this->hasMany(Offer::class, 'buyer_id');
    }

    // --- Relasi Baru untuk Sistem Chat ---

    /**
     * Relasi untuk pesan yang dikirim oleh pengguna ini, terlepas dari perannya.
     * Menggunakan sender_id di tabel messages.
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Relasi untuk percakapan di mana pengguna ini adalah pembeli.
     */
    public function buyerConversations()
    {
        return $this->hasMany(Conversation::class, 'buyer_id');
    }

    /**
     * Relasi untuk percakapan di mana pengguna ini adalah agen.
     */
    public function agentConversations()
    {
        return $this->hasMany(Conversation::class, 'agent_id');
    }

    // --- Scope untuk memfilter pengguna berdasarkan peran (sudah ada, hanya dikonfirmasi) ---

    /**
     * Scope untuk memfilter pengguna berdasarkan peran Admin.
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin')->where('is_approved', true);
    }

    /**
     * Scope untuk memfilter pengguna berdasarkan peran Agen.
     */
    public function scopeAgent($query)
    {
        return $query->where('role', 'agent')->where('is_approved', true);
    }

    /**
     * Scope untuk memfilter pengguna berdasarkan peran Pembeli.
     */
    public function scopeBuyer($query)
    {
        return $query->where('role', 'buyer')->where('is_approved', true);
    }

    // --- Helper untuk mengecek peran (opsional tapi sangat berguna) ---

    /**
     * Cek apakah pengguna adalah admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah pengguna adalah agen.
     */
    public function isAgent(): bool
    {
        return $this->role === 'agent';
    }

    /**
     * Cek apakah pengguna adalah pembeli.
     */
    public function isBuyer(): bool
    {
        return $this->role === 'buyer';
    }
}