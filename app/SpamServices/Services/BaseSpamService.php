<?php

namespace App\SpamServices\Services;

class BaseSpamService
{
    /** @var array */
    protected $formOptions;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($formOptions)
    {
        $this->formOptions = $formOptions;
    }

    /**
     * Provider id.
     */
    public function getId()
    {
        return '';
    }

    /**
     * Is provider active.
     */
    public function isEnabled()
    {
        if (!isset($this->formOptions[$this->getId()])) {
            return false;
        }

        if (!$this->formOptions[$this->getId()]) {
            return false;
        }

        return true;
    }

    /**
     * Check if the submission is spam.
     *
     * @param array $submissionData
     *   Submission data.
     *
     * @return boolean
     */
    public function checkSubmission(array $submissionData)
    {
        return true;
    }

    /**
     * Get all email fields from submission.
     *
     * @return array
     */
    public function getEmailInputs($submissionData)
    {
        $emailInputs = [];

        foreach ($submissionData as $input) {
            if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
                $emailInputs[] = $input;
            }
        }

        return $emailInputs;
    }
}
