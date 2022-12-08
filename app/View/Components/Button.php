<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{

    /**
     * The button type.
     *
     * @var string
     */
    public $type;

    /**
     * Button text.
     *
     * @var string
     */
    public $text;

    /**
     * The button icon.
     *
     * @var string
     */
    public $icon;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($text, $icon = null, $type = 'default')
    {
        $this->text = $text;
        $this->icon = $icon;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.button');
    }
}
