<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Snowfire\Beautymail\Beautymail;

class BeautyMailChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $config = $notification->beautymailSend($notifiable);
        $beautymail = app()->make(Beautymail::class);

        return $beautymail->send($config['template'], ['data' => $config['data']], function ($message) use ($config) {
            $message
                ->from($config['from_email'], $config['from_name'])
                ->to($config['to_email'], $config['to_name'])
                ->subject($config['subject']);
        });
    }
}
