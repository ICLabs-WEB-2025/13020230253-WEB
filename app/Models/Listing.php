<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    // Jika nama tabel berbeda, misalnya "houses":
    // protected $table = 'houses';

    // Kolom yang bisa diisi melalui mass-assignment (misalnya saat create atau update)
    protected $fillable = [
        'title',
        'price',
        'description',
        'location',
        'user_id', // atau agent_id, tergantung sistem kamu
        'image',
    ];

    // Contoh relasi ke User (opsional)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
