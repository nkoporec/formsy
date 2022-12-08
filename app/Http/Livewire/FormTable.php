<?php

namespace App\Http\Livewire;

use App\Repositories\FormRepository;
use App\Repositories\SubmissionRepository;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FormTable extends Component
{
    /**
     * Number of items per page.
     *
     * @var int
     */
    public $perPage = 10;

    public function render()
    {
        $submissionRepository = new SubmissionRepository();
        $formRepository = new FormRepository($submissionRepository);

        $forms = $formRepository->getAll(Auth::id());

        if ($this->perPage != 'All') {
            // Paginate.
            $forms = $formRepository->getAll(
                Auth::id(),
                true,
                $this->perPage,
            );
        }

        foreach ($forms as $form) {
            $submissions = $submissionRepository->getAll($form->machine_name);
            $form->submissions = count($submissions);
        }


        return view('livewire.form-table', [
            'forms' => $forms,
        ]);
    }
}
