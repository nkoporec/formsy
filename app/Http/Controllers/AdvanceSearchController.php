<?php

namespace App\Http\Controllers;

use App\Repositories\FormRepository;
use App\Repositories\SubmissionRepository;
use App\Submission;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

class AdvanceSearchController extends Controller
{

    /** @var \App\Repositories\FormRepository */
    protected $formRepository;

    /** @var \App\Repositories\SubmissionRepository */
    protected $submissionRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FormRepository $formRepository, SubmissionRepository $submissionRepository)
    {
        $this->middleware('auth');
        $this->formRepository = $formRepository;
        $this->submissionRepository = $submissionRepository;
    }

    /**
     *  View advance search
     *
     * @param string $id
     *   Form id.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function view()
    {
        $forms = $this->formRepository->getAll(Auth::id());
        $fields = $this->submissionRepository->getSubmissionsFields();

        return view('advance-search', [
            'forms' => $forms,
            'fields' => $fields,
        ]);
    }

    /**
     *  Advance search
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
        $params = array_filter($params['params']);

        if (!$params) {
            return response()->json([]);
        }

        // Build query.
        $submissions = Submission::where([
            'user_id' => Auth::id(),
            'spam' => 0,
        ]);

        if (isset($params['submission_id'])) {
            $submissions->where('id', $params['submission_id']);
        }

        if (isset($params['form'])) {
            $form = $this->formRepository->get($params['form']);
            $submissions->where('form_id', $form->name);
        }


        if (isset($params['date_start']) && isset($params['date_end'])) {
            $startDate = date($params['date_start']);
            $endDate = date($params['date_end']);

            $submissions->whereBetween('updated_at', [$startDate, $endDate]);
        }

        if (isset($params['date_start']) && !isset($params['date_end'])) {
            $startDate = date($params['date_start']);
            $endDate = date('Y/m/d');

            $submissions->whereBetween('updated_at', [$startDate, $endDate]);
        }


        if (!isset($params['date_start']) && isset($params['date_end'])) {
            $startDate = date('Y/m/d');
            $endDate = date($params['date_end']);

            $submissions->whereBetween('updated_at', [$startDate, $endDate]);
        }


        // Filter by custom field.
        $filteredSubmissions = [];
        foreach ($params['custom_fields'] as $item) {
            if (!$item['name'] || !$item['value']) {
                continue;
            }

            $filtered = $submissions->get()->filter(function ($submission) use ($item) {
                $submission = $this->submissionRepository->formatSubmission($submission);

                foreach ($submission->data as $field_name => $field_value) {
                    if ($field_name == $item['name'] && $field_value == $item['value']) {
                        return $submission;
                    }
                }
            });

            $filteredSubmissions[] = $filtered->all();
        }

        // Loop through all custom field results and set keys as submission IDs.
        $results = [];
        $hasCustomFieldFilter = false;
        foreach ($filteredSubmissions as $field_name => $field) {
            foreach ($field as $key => $item) {
                unset($filteredSubmissions[$field_name][$key]);
                $filteredSubmissions[$field_name][$item->id] = $item;
            }
        }

        // Compare results from all custom fields and return
        // submissions that are in all results.
        if (count($filteredSubmissions) == 1) {
            $results = end($filteredSubmissions);
            $hasCustomFieldFilter = true;
        } elseif (count($filteredSubmissions) > 1) {
            $results = array_intersect(...$filteredSubmissions);
            $hasCustomFieldFilter = true;
        }

        if (!$results && !$hasCustomFieldFilter) {
            $submissions = $submissions->get();
            foreach ($submissions as $submission) {
                $results[] = $this->submissionRepository->formatSubmission($submission);
            }
        }

        return response()->json($results);
    }
}
