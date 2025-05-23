<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentApplication extends Model
{
    protected $fillable = ['user_id', 'nik', 'address', 'phone', 'document_path', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}