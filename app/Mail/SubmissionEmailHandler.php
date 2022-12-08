<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubmissionEmailHandler extends Mailable
{
    use Queueable, SerializesModels;

    public $mailView;
    public $mailSubject;
    public $mailBody;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($mailView, $mailSubject, $mailBody)
    {
        $this->mailView = $mailView;
        $this->mailSubject = $mailSubject;
        $this->mailBody = $mailBody;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->mailView)
                    ->subject($this->mailSubject)
                    ->with(['body' => $this->mailBody]);
    }
}
