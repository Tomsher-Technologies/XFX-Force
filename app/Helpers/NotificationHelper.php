<?php

use App\Notifications\CommonNotification;

if (!function_exists('sendNotification')) {

    function sendNotification($users, $message, $order = null, $type = null, $extra = [])
    {
        if (!$users) return;

        if (is_iterable($users)) {
            foreach ($users as $user) {
                $user->notify(new CommonNotification($message, $order, $type, $extra));
            }
        } else {
            $users->notify(new CommonNotification($message, $order, $type, $extra));
        }
    }
}