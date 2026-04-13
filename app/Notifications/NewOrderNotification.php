<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $type; // 'admin' or 'customer'

    public function __construct($order, $type = 'admin')
    {
        $this->order = $order;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $name = $this->order->user_id ? $this->order->user->name : json_decode($this->order->billing_address)->name ?? 'Guest';
        
        if($this->type === 'admin') {
            $message = 'New order placed by ' . $name;
        } else {
            $message = 'Your order #' . $this->order->id . ' has been placed successfully';
        }

        return [
            'order_id' => $this->order->id,
            'user_name' => $name,
            'message' => $message,
        ];
    }
}