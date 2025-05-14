<?php

namespace App\Mail;

use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DriverAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Delivery $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('New Delivery Assignment')
            ->view('emails.driver_assigned');
    }
}

