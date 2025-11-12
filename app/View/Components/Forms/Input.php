<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Input extends Component
{
    public string $name;
    public string $label;
    public string $type;
    public ?string $icon;
    public ?string $placeholder;
    public mixed $value;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $name,
        string $label,
        string $type = 'text',
        ?string $icon = null,
        ?string $placeholder = null,
        mixed $value = null
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->type = $type;
        $this->icon = $icon;
        $this->placeholder = $placeholder;
        $this->value = $value ?? old($name);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.input');
    }
}
