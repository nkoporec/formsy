<?php

namespace App\SpamServices\Services;

class HoneypotField extends BaseSpamService {

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'honeypot_enabled';
    }

    /**
     * {@inheritdoc}
     */
    public function checkSubmission(array $submissionData)
    {
        $field = $this->formOptions['honeypot_field'];

        if (isset($submissionData[$field]) && $field) {
            return true;
        }

        return false;
    }

}
