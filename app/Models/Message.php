<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['sender_id', 'sender_type', 'message','delivery_id'];

   // Message model (App\Models\Message)
public function delivery()
{
    return $this->belongsTo(Delivery::class);
}


    public function sender()
{
    if ($this->sender_type === 'driver') {
        return $this->belongsTo(\App\Models\Driver::class, 'sender_id');
    }
    return $this->belongsTo(\App\Models\User::class, 'sender_id');
}

}
