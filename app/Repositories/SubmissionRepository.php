<?php

namespace App\Repositories;

use App\Events\SubmissionDenied;
use App\Form;
use App\Submission;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use App\Repositories\FileRepository;
use App\Repositories\UserRepository;
use App\Repositories\FormRepository;
use Illuminate\Support\Facades\Storage;

class SubmissionRepository
{

    /** @var \App\Repositories\FileRepository */
    protected $fileRepository;

    /** @var \App\Repositories\FileRepository */
    protected $formRepository;

    /** @var \App\Repositories\userRepository */
    protected $userRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->fileRepository = new FileRepository();
        $this->formRepository = new FormRepository($this);
        $this->userRepository = new UserRepository();
    }

    /**
     *  Get submission.
     *
     * @param string $id
     *   Submission id.
     * @param id $userId
     *   User id.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get($id, $userId = null)
    {
        if ($userId) {
            $submission = Submission::where([
                'id' => $id,
                'user_id' => $userId,
            ])->first();

            if (!$submission) {
                return null;
            }

            return $this->formatSubmission($submission);
        }

        $submission = Submission::where([
            'id' => $id,
        ])->first();

        if (!$submission) {
            return null;
        }

        return $this->formatSubmission($submission);
    }

    /**
     *  Create submission.
     *
     * @param array $data
     *   Array of data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function create(array $data)
    {
        // Set required data.
        if (!isset($data['form_id'])) {
            return null;
        }

        // If user is not passed, use current.
        if (!isset($data['user_id'])) {
            $data['user_id'] = Auth::id();
        }

        // Always serialize data.
        if (is_array($data['data'])) {
            // Grab all files.
            // @TODO: Refactor when time.
            foreach ($data['data'] as $key => $fileData) {
                if ($fileData instanceof UploadedFile) {
                    $form = $this->formRepository
                        ->getByMachineName($data['form_id']);

                    $userPlan = $this->userRepository
                        ->getSubscribedPlan($data['user_id']);

                    // Free user.
                    if ($userPlan['file_storage'] == null) {
                        $data['data'][$key] = $fileData->getClientOriginalName();
                        continue;
                    }

                    // Check storage.
                    // If storage full, then block the submission.
                    // @TODO: Revisit this ?
                    $userStorage = $this->fileRepository->getUsedStorage($data['user_id']);
                    if ($userStorage > $userPlan['file_storage']) {
                        event(new SubmissionDenied(
                            $form->id,
                            $form->user_id,
                            'warning',
                            "Submission file could not be saved, because your storage is full.",
                        ));

                        $data['data'][$key] = $fileData->getClientOriginalName();
                        continue;
                    }

                    // Check size.
                    if ($fileData->getSize() > $this->fileRepository->allowedUploadSize()) {
                        event(new SubmissionDenied(
                            $form->id,
                            $form->user_id,
                            'warning',
                            "Submission contained a file that was too big to save.",
                        ));

                        $data['data'][$key] = $fileData->getClientOriginalName();
                        continue;
                    }

                    // Check file extension.
                    if (!in_array($fileData->getClientOriginalExtension(), $this->fileRepository->getAllowedExtensions())) {
                        event(new SubmissionDenied(
                            $form->id,
                            $form->user_id,
                            'warning',
                            "Submission contained a file with invalid file extension.",
                        ));
                        $data['data'][$key] = $fileData->getClientOriginalName();
                        continue;
                    }

                    // Save the file.
                    $file = $this->fileRepository
                        ->create($form->id, $data['user_id'], $fileData);

                    $data['data'][$key] = [
                        'is_file' => true,
                        'id' => $file->id,
                        'name' => $file->name,
                    ];
                }
            }
        }

        $data['data'] = serialize($data['data']);
        $submission = Submission::create($data);

        return $submission;
    }

    /**
     *  Delete a submission.
     *
     * @param int $id
     *   Submission id.
     *
     * @return bool
     *  Returns true on success.
     */
    public function delete($id)
    {
        $submission = $this->get($id);
        if (!$submission) {
            return false;
        }

        // Delete all files associated with the submission.
        $data = $submission->data;
        foreach ($data as $item) {
            if (is_array($item) && isset($item['is_file'])) {
                $file = $this->fileRepository->get($item['id']);

                // Delete it on the bucket.
                Storage::disk('digitalocean')->delete($file->path);

                // Delete the model.
                $file->delete();
            }
        }

        $submission->delete();

        return true;
    }

    /**
     *  Get last submission in a form.
     *
     * @param string $machine_name
     *   Form machine name.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getLast($machine_name)
    {
        $lastSubmission = Submission::where([
            'form_id' => $machine_name,
            'user_id' => Auth::id(),
        ])->latest()->first();

        return $lastSubmission;
    }

    /**
     *  Get all form submissions.
     *
     * @param string $machine_name
     *   Form machine name.
     * @param bool $collection
     *   Return results as collection.
     * @param bool $spam
     *   Return spam submissions as well.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll($machine_name, $collection = true, $spam = false)
    {
        $spamValue = $spam ? 1 : 0;

        if ($collection) {
            $submissions = Submission::where([
                'form_id' => $machine_name,
                'user_id' => Auth::id(),
                'spam' => $spamValue,
            ])->get();

            return $submissions;
        }

        $submissions = Submission::where([
            'form_id' => $machine_name,
            'user_id' => Auth::id(),
            'spam' => $spamValue,
        ]);

        return $submissions;
    }

    /**
     *  Get the next submission.
     *
     * @param string $id
     *   Current submission id.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getNext($id)
    {
        $nextSubmissionId = Submission::where('id', '>', $id)->where([
            'user_id' => Auth::id(),
        ])->min('id');

        if ($nextSubmissionId) {
            $nextSubmission = Form::where('id', '=', $nextSubmissionId)->where([
                'user_id' => Auth::id(),
            ])->first();

            if (!$nextSubmission) {
                return null;
            }

            return $this->formatSubmission($nextSubmission);
        }

        return null;
    }

    /**
     *  Get the previous submission.
     *
     * @param string $id
     *   Current submission id.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPrevious($id)
    {
        $previousSubmissionId = Submission::where('id', '<', $id)->where([
            'user_id' => Auth::id(),
        ])->max('id');

        if ($previousSubmissionId) {
            $previousSubmission = Form::where('id', '=', $previousSubmissionId)->where([
                'user_id' => Auth::id(),
            ])->first();

            if (!$previousSubmission) {
                return null;
            }

            return $this->formatSubmission($previousSubmission);
        }

        return null;
    }


    /**
     *  Search submissions.
     *
     * @param string $keyword
     *   Keyword to search within submission.
     * @param int $userId
     *   User id.
     *
     * @return \Illuminate\Support\Collection
     */
    public function search($keyword, $userId = null, $constrain = null, $collection = true)
    {
        // @todo Refactor.
        if ($userId) {
            if ($constrain) {
                $results = Submission::search($keyword)
                    ->where('user_id', Auth::id())
                    ->where('spam', 0)
                    ->constrain($constrain)
                    ->orderBy('updated_at', 'desc')
                    ->take(10)
                    ->get();

                return $results;
            }

            if ($collection) {
                $results = Submission::search($keyword)
                    ->where('user_id', Auth::id())
                    ->where('spam', 0)
                    ->orderBy('updated_at', 'desc')
                    ->take(10)
                    ->get();
            } else {
                $results = Submission::search($keyword)
                    ->where('user_id', Auth::id())
                    ->where('spam', 0)
                    ->orderBy('updated_at', 'desc')
                    ->take(10);
            }

            return $results;
        }

        if ($constrain) {
            if ($collection) {
                $results = Submission::search($keyword)
                    ->constrain($constrain)->get();
            } else {
                $results = Submission::search($keyword)
                    ->constrain($constrain);
            }

            return $results;
        }

        if ($collection) {
            $results = Submission::search($keyword)->orderBy('updated_at', 'desc')->take(10)->get();
        } else {
            $results = Submission::search($keyword)->orderBy('updated_at', 'desc')->take(10);
        }

        return $results;
    }

    /**
     *  Formats a submission into more readable data.
     *
     * @param \App\Submission $submission
     *   Submission.
     *
     * @return \Illuminate\Support\Collection
     */
    public function formatSubmission(Submission $submission)
    {
        $form = Form::where(['machine_name' => $submission->form_id])->first();
        if (!$form) {
            return null;
        }

        $submission->form_name = $form->name;
        $submission->form_id = $form->id;
        $submission->data = unserialize($submission->data);
        $submission->first_data = current($submission->data);

        return $submission;
    }

    /**
     * Get all submission fields.
     *
     * @return array
     */
    public function getSubmissionsFields()
    {
        $fields = [];

        $submissions = Submission::where([
            'user_id' => Auth::id(),
            'spam' => 0,
        ])->get();

        foreach ($submissions as $submission) {
            $submission = $this->formatSubmission($submission);
            foreach ($submission->data as $field => $value) {
                $fields[$field] = $field;
            }
        }

        return $fields;
    }
}
