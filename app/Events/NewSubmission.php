<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewSubmission implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Form.
     *
     * @var App\Form
     */
    public $form;

    /**
     * Submission.
     *
     * @var App\Submission
     */
    public $submission;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($form, $submission)
    {
        $this->form = $form;
        $this->submission = $submission;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [];
    }
}
