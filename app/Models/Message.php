<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['order_id', 'sender_id', 'sender_type', 'message'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function sender()
{
    if ($this->sender_type === 'driver') {
        return $this->belongsTo(\App\Models\Driver::class, 'sender_id');
    }
    return $this->belongsTo(\App\Models\User::class, 'sender_id');
}

}
