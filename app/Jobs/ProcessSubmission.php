<?php

namespace App\Jobs;

use App\Events\NewSubmission;
use App\Events\SubmissionDenied;
use App\Jobs\Middleware\RateLimit;
use App\Repositories\FileRepository;
use App\Repositories\FormRepository;
use App\Repositories\SubmissionRepository;
use App\SpamServices\SpamService;
use App\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ProcessSubmission implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $retryAfter = 30;

    /**
     * The submission instance.
     *
     * @var \App\Models\Submission
     */
    protected $submission;

    /**
     *  Form repository.
     *
     * @var \App\Repositories\FormRepository
     */
    protected $formRepository;

    /**
     * Submission Repository.
     *
     * @var \App\Repositories\SubmissionRepository
     */
    protected $submissionRepository;

    /**
     * File Repository.
     *
     * @var \App\Repositories\FileRepository
     */
    protected $fileRepository;

    /**
     * Spam service.
     *
     * @var \App\SpamServices\SpamService
     */
    protected $spamService;

    /**
     * Create a new job instance.
     *
     * @param  App\Models\Submission $submission
     * @return void
     */
    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
        $this->submissionRepository = new SubmissionRepository();
        $this->formRepository = new FormRepository($this->submissionRepository);
        $this->spamService = new SpamService($this->formRepository);
        $this->fileRepository = new FileRepository();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $form = $this->formRepository->getByMachineName($this->submission->form_id);
        $submission = $this->submission;

        if (!$form) {
            event(new SubmissionDenied(
                null,
                $form->user_id,
                'error',
                "Submission was denied because of an internal error, the erorr code is 100. Please contact support.",
            ));
            return;
        }

        // Check for spam.
        $isSpam = $this->spamService->isSpam(
            $form->id,
            $submission->data,
        );

        if ($isSpam) {
            event(new SubmissionDenied(
                $form->id,
                $form->user_id,
                'spam',
                "Submission was denied because it was marked as spam.",
            ));

            $this->submission->delete();

            return;
        }

        // Check for file uploads.
        $data = $submission->data;
        foreach ($data as $key => $item) {
            if (is_array($item) && isset($item['is_file'])) {
                // @TODO: Add error handling.
                $save = $this->fileRepository->saveToBucket(
                    $this->fileRepository->get($item['id'])
                );
            }
        }

        // Fire an event that a new submission has been added.
        // This will trigger all enabled handlers.
        event(new NewSubmission($form, $this->submission));
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        // @todo Maybe add WithoutOverlapping ?
        return [
            new RateLimit,
        ];
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
            "An error has occurr while trying to process the submission,
            the error code is $exceptionCode, with message: $exceptionMessage",
        ));
    }
}
