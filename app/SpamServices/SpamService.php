<?php

namespace App\SpamServices;

use App\Repositories\FormRepository;

class SpamService
{
    /** @var \App\Repositories\FormRepository */
    protected $formRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FormRepository $formRepository)
    {
        $this->formRepository = $formRepository;
    }

    public function getSpamServiceProviders()
    {
        return [
            'App\SpamServices\Services\StopForumSpam',
            'App\SpamServices\Services\HoneypotField',
        ];
    }

    public function isSpam($formId, $submissionData)
    {
        $providers = $this->getSpamServiceProviders();
        foreach ($providers as $providerClass) {
            $provider = new $providerClass($this->formRepository->getOptions($formId));

            if (!$provider->isEnabled()) {
                continue;
            }

            $isSpam = $provider->checkSubmission($submissionData);
            if ($isSpam) {
                return true;
            }
        }

        return false;
    }
}
