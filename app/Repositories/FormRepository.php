<?php

namespace App\Repositories;

use App\Form;
use App\Repositories\SubmissionRepository;
use Illuminate\Support\Facades\Auth;

class FormRepository
{

    /** @var \App\Repositories\SubmissionRepository */
    protected $submissionRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SubmissionRepository $submissionRepository)
    {
        $this->submissionRepository = $submissionRepository;
    }

    /**
     *  Get form.
     *
     * @param string $id
     *   Form id.
     * @param id $userId
     *   User id.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get($id, $userId = null)
    {
        if ($userId) {
            $form = Form::where([
                'id' => $id,
                'user_id' => $userId,
            ])->first();

            return $form;
        }

        $form = Form::where([
            'id' => $id,
        ])->first();

        return $form;
    }

    /**
     *  Get all forms.
     *
     * @param id $userId
     *   User id.
     * @param bool $paginate
     *   Paginate results.
     * @param int $perPage
     *   Number of items per page.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll($userId, $paginate = false, $perPage = 10)
    {
        if ($paginate) {
            $forms = Form::where('user_id', '=', $userId)
                ->paginate($perPage)->getCollection();

            return $forms;
        }

        $forms = Form::where('user_id', "=", $userId)->get();

        return $forms;
    }

    /**
     *  Create form.
     *
     * @param array $data
     *   Array of data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function create(array $data)
    {
        // Set required data.
        if (!isset($data['machine_name']) || !isset($data['name'])) {
            return null;
        }

        // If user is not passed, use current.
        if (!isset($data['user_id'])) {
            $data['user_id'] = Auth::id();
        }

        // If options is not set, then use default ones.
        $options = $this->getDefaultOptions();
        if (isset($data['options'])) {
            $data['options'] = array_merge($options, $data['options']);
        } else {
            $data['options'] = $options;
        }
        $data['options'] = serialize($data['options']);

        $form = Form::create($data);

        return $form;
    }

    /**
     *  Get form by machine name.
     *
     * @param string $machineName
     *   Form machine name.
     * @param id $userId
     *   User id.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getByMachineName($machineName, $userId = null)
    {
        if ($userId) {
            $form = Form::where([
                'machine_name' => $machineName,
                'user_id' => $userId,
            ])->first();

            return $form;
        }

        $form = Form::where([
            'machine_name' => $machineName,
        ])->first();

        return $form;
    }

    /**
     *  Get the next form.
     *
     * @param string $id
     *   Current form id.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getNext($id)
    {
        $nextForm = Form::where('id', '>', $id)->where([
            'user_id' => Auth::id(),
        ])->min('id');

        if ($nextForm) {
            $nextForm = Form::where('id', '=', $nextForm)->where([
                'user_id' => Auth::id(),
            ])->first();

            return $nextForm;
        }

        return null;
    }

    /**
     *  Get the previous form.
     *
     * @param string $id
     *   Current form id.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPrevious($id)
    {
        $previousForm = Form::where('id', '<', $id)->where([
            'user_id' => Auth::id(),
        ])->max('id');

        if ($previousForm) {
            $previousForm = Form::where('id', '=', $previousForm)->where([
                'user_id' => Auth::id(),
            ])->first();

            return $previousForm;
        }

        return null;
    }

    /**
     *  Get the last submission in form.
     *
     * @param string $machine_name
     *   Form machine name.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getLastSubmission($machine_name)
    {
        $lastSubmission = $this->submissionRepository->getLast($machine_name);

        if ($lastSubmission) {
            return $lastSubmission;
        }

        return null;
    }

    /**
     *  Delete a form.
     *
     * @param string $id
     *   Form id.
     * @param bool $withSubmissions
     *   Delete the form submissions too.
     *
     * @return bool
     */
    public function delete($id, $withSubmissions = true)
    {
        $form = $this->get($id);

        if (!$form) {
            return null;
        }

        if ($withSubmissions) {
            $submissions = $this->submissionRepository->getAll($form->machine_name);
            foreach ($submissions as $submission) {
                $submission->delete();
            }
        }

        return $form->delete();
    }

    /**
     *  Change form status.
     *
     * @param string $id
     *   Form id.
     * @param bool $active
     *   Determines if active/closed.
     *
     * @return bool
     */
    public function setActive($id, $active = true)
    {
        $form = $this->get($id);

        if (!$form) {
            return null;
        }

        switch ($active) {
        case true:
            return $form->update(['status' => 1]);

        case false:
            return $form->update(['status' => 0]);
        }

        return null;
    }

    /**
     *  Get all form options.
     *
     * @param string $id
     *   Form id.
     *
     * @return bool
     */
    public function getOptions($id)
    {
        $form = $this->get($id);
        if (!$form) {
            return null;
        }

        if (!$form->options) {
            return [];
        }

        return unserialize($form->options);
    }

    /**
     *  Get form option.
     *
     * @param string $id
     *   Form id.
     * @param string $option
     *   Form option name.
     *
     * @return bool
     */
    public function getOption($id, $option)
    {
        $form = $this->get($id);
        if (!$form) {
            return null;
        }

        $options = $this->getOptions($id);

        return isset($options[$option]) ? $options[$option] : null;
    }

    /**
     *  Save form options.
     *
     * @param string $id
     *   Form id.
     * @param array $options
     *   Form options.
     *
     * @return bool
     */
    public function saveOptions($id, $options)
    {
        $form = $this->get($id);
        if (!$form) {
            return null;
        }

        $form->options = serialize($options);

        return $form->save();
    }

    /**
     *  Default form options.
     *
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'stopforumspam' => true,
        ];
    }

    /**
     * Get handler settings.
     *
     * @param string $id
     *   Form id.
     * @param string $handlerId
     *   Handler id.
     *
     * @return array
     *   Array of handler settings.
     */
    public function getHandlerSettings($id, $handlerId)
    {
        $form = $this->get($id);

        if (!$form) {
            return [];
        }

        $handler = "handler_" . $handlerId;

        $handlerSettings = $form->handlers_settings;
        if (!isset($handlerSettings[$handler])) {
            return [];
        }

        $settings = $form->handlers_settings;

        return isset($settings[$handler]) ? $settings[$handler] : [];
    }

    /**
     * Save handler settings.
     *
     * @param string $id
     *   Form id.
     * @param int $handlerId
     *   Handler id.
     * @param array $settings
     *   Handler settings.
     *
     * @return array
     *   Array of handler settings.
     */
    public function saveHandlerSettings($form_id, $handler_id, $settings)
    {
        $form = $this->get($form_id);

        if (!$form) {
            return [];
        }

        $existingData[$handler_id] = $settings;

        $form->handlers_settings = $existingData;

        $form->save();

        return true;
    }
}
