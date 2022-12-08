<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Checkbox extends Component
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
     * Checked.
     *
     * @var string
     */
    public $checked;

    /**
     * Field required.
     *
     * @var string
     */
    public $required;

    /**
     * Field description.
     *
     * @var string
     */
    public $description;

    /**
     * Create the component instance.
     *
     * @param  string  $name
     * @param  string  $id
     * @param  string  $label
     * @param  string  $checked
     * @param  string  $required
     * @param  string  $description
     * @return void
     */
    public function __construct($name, $id, $label, $checked, $required, $description = null)
    {
        $this->name = $name;
        $this->id = $id;
        $this->label = $label;
        $this->checked = $checked;
        $this->required = $required;
        $this->description = $description;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.forms.checkbox');
    }

    /**
     * Determine if the field is required.
     *
     * @return bool
     */
    public function isRequired()
    {
        if ($this->required == "true") {
            return true;
        }

        return false;
    }

    /**
     * Determine if the field is checked.
     *
     * @return bool
     */
    public function isChecked()
    {
        if ($this->checked == "true") {
            return true;
        }

        return false;
    }
}
