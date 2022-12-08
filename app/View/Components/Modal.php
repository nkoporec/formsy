<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    /**
     * The modal type.
     *
     * @var string
     */
    public $type;

    /**
     * Button text.
     *
     * @var string
     */
    public $buttonText;

    /**
     * Button icon.
     *
     * @var string
     */
    public $buttonIcon;

    /**
     * The modal message.
     *
     * @var string
     */
    public $message;

    /**
     * Create a new component instance.
     *
     * @param  string  $message
     * @param  string  $buttonText
     * @param  string  $buttonIcon
     * @param  string  $type
     * @return void
     */
    public function __construct($message, $buttonText, $buttonIcon, $type = 'default')
    {
        $this->message = $message;
        $this->type = $type;
        $this->buttonText = $buttonText;
        $this->buttonIcon = $buttonIcon;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.modal');
    }
}
