<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommonNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $message;
    protected $type;
    protected $extra;

    public function __construct($message, $order = null, $type = null, $extra = [])
    {
        $this->message = $message;
        $this->order   = $order;
        $this->type    = $type;
        $this->extra   = $extra;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $name = null;

        if ($this->order && $this->order->user_id) {
            $name = $this->order->user->name;
        } elseif ($this->order) {
            $address = json_decode($this->order->billing_address);
            $name = $address->name ?? 'Guest';
        }

        return [
            'order_id'  => $this->order->id ?? null,
            'user_name' => $name,
            'type'      => $this->type,
            'message'   => $this->message,
            'extra'     => $this->extra
        ];
    }
}