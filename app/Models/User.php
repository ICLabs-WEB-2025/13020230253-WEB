<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\House;
use App\Models\Offer;

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
        'role',
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

    /**
     * Scope untuk memfilter pengguna berdasarkan peran.
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin')->where('is_approved', true);
    }

    public function scopeAgent($query)
    {
        return $query->where('role', 'agent')->where('is_approved', true);
    }


    public function scopeBuyer($query)
    {
        return $query->where('role', 'buyer')->where('is_approved', true);
    }
}