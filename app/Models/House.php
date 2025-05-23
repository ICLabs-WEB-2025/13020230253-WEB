<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Impor model User
use App\Models\Offer; // Impor model Offer

class House extends Model
{
    // Definisikan kolom yang boleh diisi secara massal
    protected $fillable = ['agent_id', 'title', 'price', 'status', 'photo_path'];

    // Casting untuk kolom tertentu
    protected $casts = [
        'price' => 'decimal:2', // Pastikan price dianggap sebagai desimal
        'status' => 'string',   // Status sebagai string (opsional: bisa diganti dengan enum jika diperlukan)
    ];

    // Relasi dengan User (agent)
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    // Relasi dengan Offer
    public function offers()
    {
        return $this->hasMany(Offer::class, 'house_id');
    }
}