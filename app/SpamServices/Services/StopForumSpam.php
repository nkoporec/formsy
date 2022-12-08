<?php

namespace App\SpamServices\Services;

class StopForumSpam extends BaseSpamService
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'stopforumspam';
    }

    /**
     * {@inheritdoc}
     */
    public function checkSubmission(array $submissionData)
    {
        $emails = $this->getEmailInputs($submissionData);

        if (!$emails) {
            return false;
        }

        foreach ($emails as $email) {
            $isSpam = \StopForumSpam::setEmail($email)->isSpamEmail();
            if ($isSpam) {
                return true;
            }
        }

        return false;
    }
}
