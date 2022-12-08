<?php

namespace App\Handlers;

use App\Handlers\BaseHandler;
use NZTim\Mailchimp\Mailchimp as MailchimpService;

class Mailchimp extends BaseHandler
{
    /**
     * {@inheritdoc}
     */
    public function getHandlerId()
    {
        return parent::getHandlerId() . 'mailchimp';
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        $handlerSettings = $this->getSettings();
        if (isset($handlerSettings['enabled']) && $handlerSettings['enabled'] == "on") {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $emailFields = $this->getEmailFields();
        if (!$emailFields) {
            return;
        }

        $apiKey = isset($this->handlerSettings['api_key']) ? $this->handlerSettings['api_key'] : false;
        if (!$apiKey) {
            return;
        }

        $audienceId = isset($this->handlerSettings['audience_id']) ? $this->handlerSettings['audience_id'] : false;
        if (!$audienceId) {
            return;
        }

        $mailchimp = new MailchimpService($apiKey);
        $audienceLists = $mailchimp->getLists();
        $audience = null;
        foreach ($audienceLists as $list) {
            if ($list['web_id'] == $audienceId) {
                $audience = $list;
                continue;
            }
        }

        if (!$audience) {
            return;
        }

        $doubleOptin = isset($this->handlerSettings['double_optin']) ? $this->handlerSettings['double_optin'] : true;
        foreach ($emailFields as $email) {
            if ($mailchimp->check($audience['id'], $email)) {
                continue;
            }

            // @todo Implement merge fields + interests.
            $mailchimp->subscribe(
                $audience['id'],
                $email,
                $merge = [],
                $doubleOptin,
            );
        }

        return true;
    }

    /**
     * Get all email fields from submission.
     */
    public function getEmailFields()
    {
        $emailInputs = [];
        $submissionData = unserialize($this->submission->data);

        foreach ($submissionData as $input) {
            if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
                $emailInputs[] = $input;
            }
        }

        return $emailInputs;
    }
}
