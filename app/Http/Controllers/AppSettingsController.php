<?php

namespace App\Http\Controllers;

use App\Repositories\AppSettingsRepository;
use App\Repositories\FormEventRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AppSettingsController extends Controller
{

    /** @var \App\Repositories\AppSettingsRepository */
    protected $appSettings;

    /** @var \App\Repositories\FormEventRepository */
    protected $formEventRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AppSettingsRepository $appSetttingsRepository, FormEventRepository $formEventRepository)
    {
        $this->appSettings = $appSetttingsRepository;
        $this->formEventRepository = $formEventRepository;
    }

    /**
     *  Show app settings.
     */
    public function settingsOverview()
    {
        $appKey = $this->appSettings->getKey(Auth::id());

        // @TODO: Add a setting to change the submission url.
        $form_endpoint = url('/') . "/submission";

        return view('settings', [
            'appId' => $appKey,
            'form_endpoint' => $form_endpoint
        ]);
    }

    /**
     * Generate new app key.
     */
    public function generateAppKey()
    {
        $generateKey = $this->appSettings->setKey(Auth::id(), Str::random(64));

        if (!$generateKey) {
            notify()->error('Something went wrong, please try again.');
            return redirect()->back();
        }

        $this->formEventRepository->create(
            null,
            Auth::id(),
            'info',
            "New app id has been generated",
        );

        notify()->success('New app id has been successfully generated.');
        return redirect()->back();
    }
}
