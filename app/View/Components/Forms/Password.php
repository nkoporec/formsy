<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Password extends Component
{

    /**
     * The field name.
     *
     * @var string
     */
    public $name;

    /**
     * Field id.
     *
     * @var string
     */
    public $id;

    /**
     * Field label.
     *
     * @var string
     */
    public $label;

    /**
     * Field value.
     *
     * @var string
     */
    public $value;

    /**
     * Create the component instance.
     *
     * @param  string  $name
     * @param  string  $id
     * @param  string  $label
     * @param  string  $value
     * @return void
     */
    public function __construct($name, $id, $label, $value)
    {
        $this->name = $name;
        $this->id = $id;
        $this->label = $label;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.forms.password');
    }
}
