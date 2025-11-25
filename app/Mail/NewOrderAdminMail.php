<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class NewOrderAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order; // order sẽ được gửi vào view

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Đơn hàng mới từ TechStore')
                    ->markdown('email.new_order_admin');
    }
}
