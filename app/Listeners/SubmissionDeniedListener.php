<?php

namespace App\Listeners;

use App\Events\NewSubmission;
use App\Events\SubmissionDenied;
use App\Repositories\FormEventRepository;

class SubmissionDeniedListener
{
    /**
     *  Form event service.
     *
     * @var \App\Repositories\FormEventRepository
     */
    protected $formEventRepository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(FormEventRepository $formEventRepository)
    {
        $this->formEventRepository = $formEventRepository;
    }

    /**
     * Handle the event.
     *
     * @param  NewSubmission  $event
     *   Event data.
     */
    public function handle(SubmissionDenied $event)
    {
        $form_id = empty($event->form_id) ? null : $event->form_id;
        $user_id = empty($event->user_id) ? null : $event->user_id;
        $type = empty($event->type) ? 'error' : $event->type;
        $message = empty($event->message) ? null : $event->message;

        $event = $this->formEventRepository->create($form_id, $user_id, $type, $message);
    }
}
