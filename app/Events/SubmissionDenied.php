<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;

class SubmissionDenied implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Form id.
     *
     * @var int
     */
    public $form_id;

    /**
     * User id.
     *
     * @var int
     */
    public $user_id;

    /**
     * Event type.
     *
     * @var string
     */
    public $type;

    /**
     * Event message.
     *
     * @var string
     */
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($form_id, $user_id, $type, $message)
    {
        $this->form_id = $form_id;
        $this->user_id = $user_id;
        $this->type = $type;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('forms.' . $this->form_id);
    }
}
