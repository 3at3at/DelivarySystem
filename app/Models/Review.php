<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Review extends Model
{
    protected $fillable = ['client_id', 'driver_id', 'rating', 'review', 'delivery_id'];

    public function client() {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function driver() {
        return $this->belongsTo(Driver::class);
    }

    public function delivery() {
        return $this->belongsTo(Delivery::class);
    }
}

