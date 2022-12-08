<?php

namespace App\Listeners;

use App\Events\NewSubmission;
use App\Repositories\FormEventRepository;
use App\Repositories\FormRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\SubmissionRepository;
use Illuminate\Support\Facades\Auth;

class Notification
{

    /** @var \App\Repositories\FormRepository */
    protected $formRepository;

    /** @var \App\Repositories\SubmissionRepository */
    protected $submissionRepository;

    /** @var \App\Repositories\NotificationRepository */
    protected $notificationRepository;

    /** @var \App\Repositories\FormEventRepository */
    protected $formEventRepository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->submissionRepository = new SubmissionRepository();
        $this->formRepository = new FormRepository($this->submissionRepository);
        $this->notificationRepository = new NotificationRepository();
        $this->formEventRepository = new FormEventRepository();
    }

    /**
     * Handle the event.
     *
     * @param  NewSubmission  $event
     *   Event data.
     */
    public function handle(NewSubmission $event)
    {
        $form = $event->form;
        $name = $form->name;
        $this->formEventRepository->create(
            $form->id,
            $form->user_id,
            'info',
            "New submission for form <b>$name</b> has been added.",
        );

        $notification = $this->notificationRepository->create(
            $form->user_id,
            'message',
            "New submission for form $name has been added.",
        );
    }
}
