<?php

namespace App\Jobs;

use App\Events\SubmissionDenied;
use App\Form;
use App\Jobs\Middleware\RateLimit;
use App\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ProcessHandler implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $retryAfter = 30;

    /**
     * The handler settings.
     *
     * @var array
     */
    protected $handlerSettings;

    /**
     * The form instance.
     *
     * @var \App\Models\Form
     */
    protected $form;

    /**
     * The submission instance.
     *
     * @var \App\Models\Submission
     */
    protected $submission;

    /**
     * The handler class name.
     *
     * @var string
     */
    protected $handlerClass;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $handlerSettings, Form $form, Submission $submission, $handlerClass)
    {
        $this->handlerSettings = $handlerSettings;
        $this->form = $form;
        $this->submission = $submission;
        $this->handlerClass = $handlerClass;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $handlerClass = $this->handlerClass;
        $handler = new $handlerClass(
            $this->handlerSettings,
            $this->form,
            $this->submission,
        );

        if (!$handler->isActive()) {
            return;
        }

        $handler->execute();
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [new RateLimit];
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addMinutes(2);
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        $form = $this->formRepository->getByMachineName($this->submission->form_id);
        $exceptionCode = $exception->getCode();
        $exceptionMessage = $exception->getMessage();

        event(new SubmissionDenied(
            $form->id,
            $form->user_id,
            'error',
            "An error has occurr while trying to process the submission handler,
            the error code is $exceptionCode, with message: $exceptionMessage",
        ));
    }
}
