<?php

namespace App\Http\Controllers;

use App\Repositories\SubmissionRepository;
use Illuminate\Http\Request;
use App\Dto\FormData;
use App\Events\SubmissionDenied;
use App\Jobs\ProcessSubmission;
use App\Repositories\FormRepository;
use App\Repositories\AppSettingsRepository;
use App\Repositories\FormEventRepository;
use App\Submission;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    const INPUT_PREFIX = "_formsy_";

    /** @var \App\Repositories\FormRepository */
    protected $formRepository;

    /** @var \App\Repositories\AppSettingsRepository */
    protected $appSettings;

    /** @var \App\Repositories\SubmissionRepository */
    protected $submissionRepository;

    /** @var \App\Repositories\FormEventRepository */
    protected $formEventRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        FormRepository $formRepository,
        AppSettingsRepository $appSetttings,
        SubmissionRepository $submissionRepository,
        FormEventRepository $formEventRepository
    ) {
        $this->formRepository = $formRepository;
        $this->appSettings = $appSetttings;
        $this->submissionRepository = $submissionRepository;
        $this->formEventRepository = $formEventRepository;
    }

    public function store(Request $request)
    {
        $formData = FormData::fromRequest($request);

        // Find existing form_name or create a new form entry in DB
        $form = $this->formRepository->getByMachineName(
            $formData->appData->formId,
            $this->appSettings->getUserByKey($formData->appData->appId),
        );

        if (!$form) {
            $form = $this->formRepository->create([
                'machine_name' => $formData->appData->formId,
                'name' => $formData->appData->name,
                'user_id' => $this->appSettings->getUserByKey(
                    $formData->appData->appId
                ),
                'status' => 1,
            ]);
        }

        // Make sure the form is opened to new submisisons.
        if ($form->status == 0) {
            event(new SubmissionDenied(
                $form->id,
                $form->user_id,
                'error',
                "Can't accept new submissions because the form is closed.",
            ));

            return $this->showFailedPage();
        }

        // Create a new submission.
        $submission = $this->submissionRepository->create([
            'form_id' => $formData->appData->formId,
            'data' => $formData->submissionData,
            'user_id' => $this->appSettings->getUserByKey(
                $formData->appData->appId
            ),
            'spam' => 0,
        ]);

        if (!$submission) {
            event(new SubmissionDenied(
                $form->id,
                $form->user_id,
                'error',
                "Can't save the submission, because of internal error, please contact support.",
            ));

            return $this->showFailedPage();
        }

        dispatch((new ProcessSubmission($submission))->onQueue('submission'));

        // Redirect back to the form, using _formsy_redirect-url input.
        if ($formData->appData->redirectUrl) {
            return redirect($formData->appData->redirectUrl);
        }

        // Redirect using redirect field option.
        $redirectOption = $this->formRepository->getOption(
            $form->id,
            'redirect_url'
        );
        if ($redirectOption) {
            return redirect($redirectOption);
        }

        return $this->showSuccessPage();
    }

    /**
     *  View a submission.
     *
     * @param string $form_name
     *   Form name.
     * @param string $id
     *   Submission id.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function view($form_name, $submission_id)
    {
        $submission = $this->submissionRepository->get(
            $submission_id,
            Auth::id(),
        );

        if (!$submission) {
            // @TODO: Add logging and better error message.
            abort(404);
        }

        return view('submission', [
            'submission' => $submission,
            'previous_submission' => $this->submissionRepository->getPrevious($submission->id),
            'next_submission' => $this->submissionRepository->getNext($submission->id),
        ]);
    }

    /**
     * Search for submissions.
     *
     * @param \Illuminate\Request $request
     *  Ajax request.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function search(Request $request)
    {
        $params = $request->all();
        if (!isset($params['params'])) {
            return;
        }
        $params = $params['params'];

        $form = isset($params['form']) ? $params['form'] : null;
        if (!$form) {
            return;
        }

        $keyword = isset($params['search']) ? $params['search'] : null;
        if (!$keyword) {
            return response()->json([]);
        }

        $results = [];

        $submissions = Submission::search($keyword)
            ->where('user_id', Auth::id())
            ->where('spam', 0)
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($submissions as $submission) {
            $results[] = $this->submissionRepository->formatSubmission($submission);
        }

        return response()->json($results);
    }


    /**
     * Delete multiple submissions.
     *
     * @param \Illuminate\Request $request
     *  Ajax request.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     *
     */
    public function delete(Request $request)
    {
        $params = $request->all();
        if (!isset($params['params'])) {
            return;
        }
        $params = $params['params'];

        $submissions = isset($params['submissions']) ? $params['submissions'] : null;
        if (!$submissions) {
            notify()->error("No submissions selected.");
            return;
        }

        foreach ($submissions as $id) {
            $submission = $this->submissionRepository->get($id);

            $form = $this->formRepository->get($submission->form_id);
            $name = $form->name;
            $this->formEventRepository->create(
                $form->id,
                Auth::id(),
                'info',
                "Submission in form <b>$name</b> has been deleted.",
            );

            $this->submissionRepository->delete($id);
        }


        notify()->success("Submissions successfully deleted.");

        return response()->json(true);
    }

    /**
     * Show success page.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function showSuccessPage()
    {
        $referer = request()->headers->get('referer');

        return view("success", ['referer' => $referer]);
    }

    /**
     * Show failed page.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function showFailedPage()
    {
        $referer = request()->headers->get('referer');

        return view("failed", ['referer' => $referer]);
    }
}
