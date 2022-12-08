<?php

namespace App\Http\Livewire;

use App\Repositories\FormEventRepository;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class FormEvents extends Component
{
    use WithPagination;

    /**
     * Number of items per page.
     *
     * @var int
     */
    public $perPage = 30;

    /**
     * Form.
     *
     * @var \App\Form
     */
    public $form;

    /**
    * Mount data.
    */
    public function mount($form)
    {
        $this->form = $form;
    }

    public function render()
    {
        $formEventsRepository = new FormEventRepository();

        $events = $formEventsRepository->getFormEvents(
            $this->form->id,
            Auth::id(),
            $this->perPage,
        );

        return view('livewire.form-events', [
            'events' => $events,
        ]);
    }
}
