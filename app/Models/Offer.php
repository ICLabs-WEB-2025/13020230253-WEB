<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = ['house_id', 'buyer_id', 'offer_price', 'message', 'status'];

    public function house()
    {
        return $this->belongsTo(House::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}