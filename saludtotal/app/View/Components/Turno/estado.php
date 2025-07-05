<?php

namespace App\View\Components\Turno;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class estado extends Component
{
    /**
     * Create a new component instance.
     */
    public string $label;
    public string $valor;

    public function __construct(string $label, string $valor)
    {
        $this->label = $label;
        $this->valor = $valor;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.turno.estado');
    }
}
