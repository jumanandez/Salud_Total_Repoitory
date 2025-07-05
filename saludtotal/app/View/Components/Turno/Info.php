<?php

namespace App\View\Components\Turno;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Info extends Component
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

    public function render()
    {
        return view('components.turno.info');
    }
}
