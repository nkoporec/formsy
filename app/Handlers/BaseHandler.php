<?php

namespace App\Handlers;

use App\Form;
use App\Repositories\FormEventRepository;
use App\Repositories\FormRepository;
use App\Repositories\SubmissionRepository;
use App\Submission;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

class BaseHandler implements BaseHandlerInterface
{
    /**
     *  Handler settings.
     *
     * @var array
     */
    protected $handler_settings;

    /**
     * Form.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $form;

    /**
     * Submission.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $submission;

    /**
     * Create a new base handler instance.
     */
    public function __construct(array $handler_settings, Form $form, Submission $submission = null)
    {
        $this->handlerSettings = $handler_settings;
        $this->form = $form;
        $this->submission = $submission;
    }

    /**
     * {@inheritdoc}
     */
    public function getHandlerId()
    {
        return 'handler_';
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function hasConditions()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getSettings()
    {
        return $this->handlerSettings;
    }

    /**
     * {@inheritdoc}
     */
    public function saveSettings($form_id, Request $request)
    {
        $formData = array_filter($request->all());
        unset($formData['_token']);
        unset($formData['q']);

        if ($this->hasConditions()) {
            // @todo Refactor.
            $conditionalData = [];
            foreach ($formData as $key => $data) {
                if (strpos($key, 'condition') !== false) {
                    $conditionalData[$key] = $data;
                    unset($formData[$key]);
                }
            }

            $conditionals = [];
            foreach ($conditionalData as $fieldName => $fieldValue) {
                $id = str_replace(
                    ['condition_', '_field_name', '_field_value'],
                    '',
                    $fieldName,
                );

                if (strpos($fieldName, '_field_name') !== false) {
                    $conditionals[$id]['field_name'] = $fieldValue;
                }

                if (strpos($fieldName, '_field_value') !== false) {
                    $conditionals[$id]['field_value'] = $fieldValue;
                }
            }
        }

        $handlerSettingsId = $this->getHandlerId();

        // Reset all values before saving new ones.
        $settings = [];
        foreach ($formData as $key => $value) {
            $settings[$key] = $value;
        }

        // Save conditionals.
        if ($this->hasConditions() && $conditionals) {
            foreach ($conditionals as $id => $condition) {
                $settings['conditionals'][$id] = $condition;
            }
        }

        $submissionRepository = new SubmissionRepository();
        $formRepository = new FormRepository($submissionRepository);
        $handlerSettings = $formRepository->saveHandlerSettings(
            $this->form->id,
            $handlerSettingsId,
            $settings,
        );


        $handlerName = ucfirst(str_replace('handler_', '', $this->getHandlerId()));
        $name = $this->form->name;
        $formEventRepository = new FormEventRepository();
        $formEventRepository->create(
            $this->form->id,
            Auth::id(),
            'info',
            "<b>$handlerName</b> handler settings for form <b>$name</b> has been updated.",
        );

        return $handlerSettings;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function convertTokens($text)
    {
        //@todo Figure out how to convert file uploads.
        // Get all tokens.
        // Tokens are defined as: [@token]
        preg_match_all("/\\[(.*?)\\]/", $text, $matches);
        $matches = isset($matches[1]) ? $matches[1] : null;
        if (!$matches || !$this->submission) {
            return $text;
        }

        // Get submission data.
        $submissionData = unserialize($this->submission->data);
        foreach ($matches as $token) {
            $tokenName = str_replace(['[', '@', ']'], '', $token);
            if (!isset($submissionData[$tokenName])) {
                continue;
            }

            $tokenValue = $submissionData[$tokenName];

            if (is_array($tokenValue) && isset($tokenValue['is_file'])) {
                $text = str_replace('[' . $token . ']', $tokenValue['name'], $text);
            } else {
                $text = str_replace('[' . $token . ']', $tokenValue, $text);
            }
        }

        return $text;
    }


    /**
     * {@inheritdoc}
     */
    public function validateConditionals()
    {
        $settings = $this->getSettings();
        if (!isset($settings['conditionals'])) {
            return true;
        }

        $submissionData = unserialize($this->submission->data);
        foreach ($settings['conditionals'] as $condition) {
            // Check that field exists.
            if (!isset($submissionData[$condition['field_name']])) {
                return false;
            }

            // Check that field value match.
            $submittedValue = $submissionData[$condition['field_name']];
            if ($submittedValue != $condition['field_value']) {
                return false;
            }
        }

        return true;
    }
}
