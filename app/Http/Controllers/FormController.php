<?php

namespace App\Http\Controllers;

use App\Exports\SubmissionExport;
use App\Repositories\FileRepository;
use App\Repositories\FormEventRepository;
use App\Repositories\FormRepository;
use App\Repositories\SubmissionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Request;
use Maatwebsite\Excel\Facades\Excel;

class FormController extends Controller
{

    /** @var \App\Repositories\FormRepository */
    protected $formRepository;

    /** @var \App\Repositories\FormEventRepository */
    protected $formEventRepository;

    /** @var \App\Repositories\SubmissionRepository */
    protected $submissionRepository;

    /** @var \App\Repositories\FileRepository */
    protected $fileRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FormRepository $formRepository, SubmissionRepository $submissionRepository, FormEventRepository $formEventRepository, FileRepository $fileRepository)
    {
        $this->middleware('auth');
        $this->formRepository = $formRepository;
        $this->formEventRepository = $formEventRepository;
        $this->submissionRepository = $submissionRepository;
        $this->fileRepository = $fileRepository;
    }

    /**
     *  View a form.
     *
     * @param string $id
     *   Form id.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function view($id)
    {
        $form = $this->formRepository->get($id, Auth::id());
        // Flash a warning for closed forms.
        if ($form->status == 0) {
            notify()->warning('This form is closed for new submissions.');
        }

        $submissions = $this->submissionRepository->getAll($form->machine_name, false);
        $numberOfSubmissions = $submissions->count();
        $submissions = $submissions->paginate(10);

        // Format the submission data.
        foreach ($submissions as $key => $submission) {
            $submissions[$key] = $this->submissionRepository->formatSubmission($submission);
        }

        return view('form', [
            'form' => $form,
            'submissions' => $submissions,
            'number_of_submissions' => $numberOfSubmissions,
            'next_form' =>  $this->formRepository->getNext($id),
            'previous_form' => $this->formRepository->getPrevious($id),
            'last_submission' => $this->formRepository
                ->getLastSubmission($form->machine_name),
        ]);
    }


    /**
     *  Show delete form page.
     *
     * @param string $id
     *   Form id.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function showDeleteForm($id)
    {
        $form = $this->formRepository->get($id);
        if (!$form) {
            return abort(404);
        }

        return view('form-delete', [
            'form' => $form,
        ]);
    }

    /**
     *  Delete a form.
     *
     * @param string $id
     *   Form id.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function deleteForm($id)
    {
        $data = request()->all();
        if (!isset($data['form-name']) || !$id) {
            notify()->error('Something went wrong while trying to delete a form. Please try again.');
            return redirect('/');
        }

        $form = $this->formRepository->get($id);
        if (!$form) {
            notify()->error('Something went wrong while trying to delete a form. Please try again.');
            return redirect('/');
        }

        $confirmFormName = $data['form-name'];
        if ($form->name != $confirmFormName) {
            notify()->error('Form name does not match, please try again.');
            return redirect()->back();
        }

        $delete = $this->formRepository->delete($id);

        if (!$delete) {
            notify()->error('Something went wrong while trying to delete a form. Please try again.');
            return redirect('/');
        }

        notify()->success('Form has been deleted successfully.');
        return redirect('/');
    }

    /**
     *  Change the form status.
     *
     * @param string $id
     *   Form id.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function statusChange($id)
    {
        $form = $this->formRepository->get($id);
        if (!$form) {
            notify()->error('Something went wrong while trying to delete a form. Please try again.');
            return redirect()->back();
        }
        $name = $form->name;

        $result = null;
        switch ($form->status) {
            case 0:
                $result = $this->formRepository->setActive($id);

                $this->formEventRepository->create(
                    $id,
                    Auth::id(),
                    'info',
                    "Form <b>$name</b> has been opened for submissions.",
                );
                break;

            case 1:
                $result = $this->formRepository->setActive($id, false);

                $this->formEventRepository->create(
                    $id,
                    Auth::id(),
                    'info',
                    "Form <b>$name</b> has been closed for submissions.",
                );
                break;
        }

        if (!$result) {
            notify()->error('Something went wrong while trying to delete a form. Please try again.');
            return redirect()->back();
        }

        notify()->success('Form status has been updated successfully.');
        return redirect()->back();
    }

    /**
     *  Export form submissions.
     *
     * @param string $id
     *   Form id.
     *
     * @return BinaryFileResponse
     */
    public function exportSubmissions($id)
    {
        $form = $this->formRepository->get($id);
        $submissions = $this->submissionRepository->getAll($form->machine_name);

        $name = $form->name;
        $this->formEventRepository->create(
            $id,
            Auth::id(),
            'info',
            "Submissions for form <b>$name</b> has been exported successfully.",
        );

        return Excel::download(
            new SubmissionExport($submissions),
            "{$form->machine_name}-submissions.csv",
        );
    }

    /**
     *  Show form events.
     *
     * @param string $id
     *   Form id.
     *
     * @return BinaryFileResponse
     */
    public function showFormEvents($id)
    {
        $form = $this->formRepository->get($id, Auth::id());
        $events = $this->formEventRepository->getFormEvents($form->id);

        return view('elements.form.events', [
            'form' => $form,
            'submissions' => $this->submissionRepository
                ->getAll($form->machine_name),
            'number_of_submissions' => $this->submissionRepository
                ->getAll($form->machine_name)->count(),
            'next_form' =>  $this->formRepository->getNext($id),
            'previous_form' => $this->formRepository->getPrevious($id),
            'last_submission' => $this->formRepository
                                      ->getLastSubmission($form->machine_name),
            'events' => $events,
        ]);
    }

    /**
     *  Save form options.
     *
     * @param string $id
     *   Form id.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function saveOptions($id, Request $request)
    {
        $data = $request->all();
        unset($data['_token']);

        $options = [];
        foreach ($data as $key => $value) {
            $options[$key] = $value;
        }

        $result = $this->formRepository->saveOptions($id, $options);
        if (!$result) {
            notify()->error('Something went wrong while trying to delete a form. Please try again.');
            return redirect()->back();
        }

        $form = $this->formRepository->get($id, Auth::id());
        $name = $form->name;
        $this->formEventRepository->create(
            $form->id,
            Auth::id(),
            'info',
            "Form <b>$name</b> options have been updated.",
        );

        notify()->success('Form options has been updated successfully.');
        return redirect()->back();
    }

    /**
     *  Download file.
     *
     * @param string $id
     *   File id.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function downloadFile($id)
    {
        $file = $this->fileRepository->get($id);

        if (!$file || $file->user_id != Auth::id()) {
            abort(404);
        }

        return Storage::disk('digitalocean')
            ->download($file->path, $file->name);
    }
}
