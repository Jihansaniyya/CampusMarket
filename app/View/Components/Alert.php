<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Alert extends Component
{
    public string $type;
    public string $message;

    /**
     * Create a new component instance.
     */
    public function __construct($type = 'info', $message = '')
    {
        $this->type = $type;
        $this->message = $message;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alert');
    }

    public function getIcon(): string
    {
        return match($this->type) {
            'success' => 'fa-check-circle',
            'error' => 'fa-exclamation-circle',
            'warning' => 'fa-exclamation-triangle',
            default => 'fa-info-circle',
        };
    }

    public function getColorClasses(): string
    {
        return match($this->type) {
            'success' => 'bg-green-50 border-green-200 text-green-700',
            'error' => 'bg-red-50 border-red-200 text-red-700',
            'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-700',
            default => 'bg-blue-50 border-blue-200 text-blue-700',
        };
    }
}
