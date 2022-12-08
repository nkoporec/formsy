<?php

namespace App\Http\Livewire;

use App\Repositories\FormRepository;
use App\Repositories\SubmissionRepository;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HeaderSearch extends Component
{

    /**
     * Search keyword
     *
     * @var string
     */
    public $searchKeyword;

    /**
     * Render component.
     */
    public function render()
    {
        // @todo Find a way to move it under constructor.
        $submissionRepository = new SubmissionRepository();
        $formRepository = new FormRepository($submissionRepository);

        $submissions = [];
        if ($this->searchKeyword) {
            $submissions = $submissionRepository
                ->search($this->searchKeyword, Auth::id());

            foreach ($submissions as $submission) {
                $submission->form = $formRepository->getByMachineName(
                    $submission->form_id
                );

                $submission->data = unserialize($submission->data);
                // This might not be the best solution if the pointers resets.
                $submission->first_data = current($submission->data);
            }
        }

        return view('livewire.header-search', ['submissions' => $submissions]);
    }
}
