<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TurnoLink extends Component
{
    public $href;
    public $img;
    public $label;

    public function __construct($href, $img, $label)
    {
        $this->href = $href;
        $this->img = $img;
        $this->label = $label;
    }

    public function render()
    {
        return view('components.turno-link');
    }
}