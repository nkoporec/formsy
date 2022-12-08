<?php

namespace App\Notifications;

use App\Channels\BeautyMailChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FormsyEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($type, array $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [BeautyMailChannel::class];
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function beautymailSend($notifiable)
    {
        $emailTemplate = null;
        $subject = null;

        switch ($this->type) {
            case "welcome":
                $emailTemplate = "emails.welcome";
                $subject = "Getting started with ${env('APP_NAME')}";
                break;

            case "reset_password":
                $emailTemplate = "emails.reset_password";
                $subject = "${env('APP_NAME')} password change request";
                break;
        }

        if (!$emailTemplate || !$subject) {
            return;
        }

        return [
            'template' => $emailTemplate,
            'subject' => $subject,
            'from_email' => env('APP_EMAIL'),
            'from_name' => env('APP_NAME'),
            'to_email' => $notifiable->email,
            'to_name' => $notifiable->name,
            'data' => $this->data,
        ];
    }
}
