<?php

namespace App\Http\Controllers;

use App\Repositories\FormEventRepository;
use App\Repositories\FormRepository;
use App\Repositories\SubmissionRepository;

class EventsController extends Controller
{

    /** @var \App\Repositories\FormRepository */
    protected $formRepository;

    /** @var \App\Repositories\FormEventRepository */
    protected $formEventRepository;

    /** @var \App\Repositories\SubmissionRepository */
    protected $submissionRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FormRepository $formRepository, SubmissionRepository $submissionRepository, FormEventRepository $formEventRepository)
    {
        $this->middleware('auth');
        $this->formRepository = $formRepository;
        $this->formEventRepository = $formEventRepository;
        $this->submissionRepository = $submissionRepository;
    }


    /**
     *  View events.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function view()
    {
        $events = $this->formEventRepository->getAll(null, 30);

        return view('events', [
            'events' => $events,
        ]);
    }

    /**
     *  Delete all events.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete()
    {
        $events = $this->formEventRepository->getAll(null, false);

        foreach ($events as $event) {
            $event->delete();
        }

        notify()->success('Events cleared successfully.');

        return redirect('events');
    }
}
