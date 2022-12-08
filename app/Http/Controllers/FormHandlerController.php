<?php

namespace App\Http\Controllers;

use App\Repositories\FormRepository;
use App\Services\FormHandlerDiscovery;
use Illuminate\Http\Request;

class FormHandlerController extends Controller
{

    /** @var \App\Services\FormHandlerDiscovery */
    protected $handlerDiscovery;

    /** @var \App\Repositories\FormRepository */
    protected $formRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FormHandlerDiscovery $formHandlerDiscovery, FormRepository $formRepository)
    {
        $this->middleware('auth');
        $this->handlerDiscovery = $formHandlerDiscovery;
        $this->formRepository = $formRepository;
    }

    /**
     *  Saves a handler form.
     *
     * @param string $id
     *   Form id.
     * @param string $handler_id
     *   Form id.
     * @param \Illuminate\Routing\Redirector|\Illuminate\Http\Request $request
     *   Current request.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function saveHandlerForm($form_id, $handler_id, Request $request)
    {
        $form = $this->formRepository->get($form_id);
        $handlers = $this->handlerDiscovery->getHandlers($form);

        // Call the submit function of the handler.
        $handlerIds = array_keys($handlers);
        if (in_array($handler_id, $handlerIds)) {
            $handlerSettings = $this->formRepository->getHandlerSettings($form_id, $handler_id);
            $form = $this->formRepository->get($form_id);
            $handler = new $handlers[$handler_id]($handlerSettings, $form);
            $handler->saveSettings($form_id, $request);

            notify()->success('Handler settings saved successfully.');
            return redirect()->back();
        }

        notify()->error("Can't save the handler form, please try again later.");
        return redirect()->back();
    }
}
