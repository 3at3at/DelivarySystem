<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'client_id', 'driver_id', 'pickup_location', 'dropoff_location', 'status', 'price'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function delivery()
{
    return $this->hasOne(Delivery::class);
}
public function messages()
{
    return $this->hasMany(Message::class);
}

}

