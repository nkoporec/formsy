<?php

namespace App\Handlers;

use App\Handlers\BaseHandler;
use App\Jobs\UserMailerJob;
use App\Mail\SubmissionEmailHandler;

class Gmail extends BaseHandler
{
    /**
     * {@inheritdoc}
     */
    public function getHandlerId()
    {
        return parent::getHandlerId() . 'gmail';
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
        $handlerSettings = $this->getSettings();

        if (!$this->validateConditionals()) {
            return;
        }

        $gmailEmailSettings = [
            'smtp_host'    => 'smtp.googlemail.com',
            'smtp_port'    => '465',
            'smtp_username'  => $handlerSettings['username'],
            'smtp_password'  => $handlerSettings['password'],
            'smtp_encryption'  => 'ssl',
            'from_email'    => $handlerSettings['from_email'],
            'from_name'    => $handlerSettings['from_name'],
        ];

        $handlerSettings['email_body'] = $this->convertTokens(
            $handlerSettings['email_body']
        );


        return dispatch((new UserMailerJob(
            $gmailEmailSettings,
            $handlerSettings['recipients'],
            new SubmissionEmailHandler(
                'handlers.gmail-email',
                $handlerSettings['subject'],
                $handlerSettings['email_body'],
            ),
        ))->onQueue('handler-execute'));
    }
}
