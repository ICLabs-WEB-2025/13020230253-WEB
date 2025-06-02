<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['buyer_id', 'agent_id', 'house_id'];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function house()
    {
        return $this->belongsTo(House::class);
    }
}