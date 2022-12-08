<?php

namespace App\Listeners;

use App\Events\NewSubmission;
use App\Jobs\ProcessHandler;
use App\Repositories\FormRepository;
use App\Repositories\SubmissionRepository;
use App\Services\FormHandlerDiscovery;

class FormHandlers
{
    /** @var \App\Services\FormHandlerDiscovery */
    protected $handlerDiscovery;

    /** @var \App\Repositories\FormRepository */
    protected $formRepository;

    /** @var \App\Repositories\SubmissionRepository */
    protected $submissionRepository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->handlerDiscovery = new FormHandlerDiscovery();
        $this->submissionRepository = new SubmissionRepository();
        $this->formRepository = new FormRepository($this->submissionRepository);
    }

    /**
     * Handle the event.
     *
     * @param  NewSubmission  $event
     *   Event data.
     */
    public function handle(NewSubmission $event)
    {
        $handlers = $this->handlerDiscovery->getHandlers($event->form, true);

        foreach ($handlers as $id => $handlerClass) {
            $handlerSettings = $this->formRepository->getHandlerSettings($event->form->id, $id);
            $handler = new $handlerClass($handlerSettings, $event->form, $event->submission);
            if ($handler->isActive()) {
                dispatch(new ProcessHandler(
                    $handlerSettings,
                    $event->form,
                    $event->submission,
                    $handlerClass,
                ))->onQueue('handler');
            }
        }
    }
}
