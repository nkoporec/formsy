<?php

/**
 * Helper functions.
 */

use App\Repositories\AppSettingsRepository;
use App\Repositories\FormRepository;
use App\Repositories\SubmissionRepository;
use Illuminate\Support\Facades\Auth;

/**
 * Retrieve app id/key.
 */
if (!function_exists('formsy_app_id')) {
    function formsy_app_id()
    {
        $appSettings = new AppSettingsRepository();
        return $appSettings->getKey(Auth::id());
    }
}

/**
 * Get current form from route.
 */
if (! function_exists('formsy_get_form')) {
    function formsy_get_form()
    {
        return request()->route()->parameter('id');
    }
}

/**
 * Get handler settings.
 */
if (! function_exists('formsy_handler_get_setting')) {
    function formsy_handler_get_setting($handler, $name)
    {
        $form_id = formsy_get_form();
        $submissionRepository = new SubmissionRepository();
        $formRepository = new FormRepository($submissionRepository);
        $handlerSettings = $formRepository->getHandlerSettings(
            $form_id,
            $handler,
        );

        return isset($handlerSettings[$name]) ? $handlerSettings[$name] : null;
    }
}

/**
 * Get form conditional.
 */
if (! function_exists('formsy_form_get_conditionals')) {
    function formsy_form_get_conditionals($handler)
    {
        $conditionals = formsy_handler_get_setting($handler, 'conditionals');
        if (!$conditionals) {
            $conditionals = [];
        }

        $conditionals[] = [
            'field_name' => null,
            'field_value' => null,
        ];

        return $conditionals;
    }
}

/**
 * Get form options.
 */
if (! function_exists('formsy_form_get_option')) {
    function formsy_form_get_option($option)
    {
        $form_id = formsy_get_form();
        $submissionRepository = new SubmissionRepository();
        $formRepository = new FormRepository($submissionRepository);

        return $formRepository->getOption($form_id, $option);
    }
}
