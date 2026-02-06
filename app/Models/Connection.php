<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Connection extends Model
{
    protected $fillable = [
        'user_id_low',
        'user_id_high',
        'status',
        'action_user',
    ];

    public function userLow()
    {
        return $this->belongsTo(User::class, 'user_id_low');
    }

    public function userHigh()
    {
        return $this->belongsTo(User::class, 'user_id_high');
    }
}
