<?php

namespace App\Handlers;

use App\Handlers\BaseHandler;
use App\Jobs\UserMailerJob;
use App\Mail\SubmissionEmailHandler;

class Sendgrid extends BaseHandler
{
    /**
     * {@inheritdoc}
     */
    public function getHandlerId()
    {
        return parent::getHandlerId() . 'sendgrid';
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

        $sendgridEmailSettings = [
            'smtp_host'    => 'smtp.sendgrid.net',
            'smtp_port'    => '587',
            'smtp_username'  => 'apikey',
            'smtp_password'  => $handlerSettings['password'],
            'smtp_encryption'  => 'tls',
            'from_email'    => $handlerSettings['from_email'],
            'from_name'    => $handlerSettings['from_name'],
        ];

        $handlerSettings['email_body'] = $this->convertTokens(
            $handlerSettings['email_body'],
        );

        return dispatch((new UserMailerJob(
            $sendgridEmailSettings,
            $handlerSettings['recipients'],
            new SubmissionEmailHandler(
                'handlers.sendgrid-email',
                $handlerSettings['subject'],
                $handlerSettings['email_body'],
            ),
        ))->onQueue('handler-execute'));
    }
}
