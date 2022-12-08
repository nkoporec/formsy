<?php

namespace App\Http\Middleware;

use App\Events\SubmissionDenied;
use App\Repositories\AppSettingsRepository;
use App\Repositories\UserRepository;
use Closure;

class CheckSubmission
{

    /** @var \App\Repositories\AppSettingsRepository */
    protected $appSettings;

    /** @var \App\Repositories\UserRepository */
    protected $userRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AppSettingsRepository $appSettings, UserRepository $userRepository)
    {
        $this->appSettings = $appSettings;
        $this->userRepository = $userRepository;
    }

    /**
     * Handle an incoming submission request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $appKey = $request->get('_formsy_app-id');

        // Check the app id.
        if (!$appKey || !$this->appSettings->keyExists($appKey)) {
            return redirect()->back();
        }

        // Check the existance of the form_id.
        $userId = $this->appSettings->getUserByKey($appKey);
        if (!$request->get('_formsy_form-id')) {
            event(new SubmissionDenied(
                null,
                $userId,
                'error',
                "Can't save the submission, because it's missing the form-id parameter, which is required.",
            ));

            return redirect()->back();
        }

        return $next($request);
    }
}
