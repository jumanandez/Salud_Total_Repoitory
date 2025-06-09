<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FooterIcon extends Component
{
    public $icon;
    public $alt;

    /**
     * Create a new component instance.
     */
    public function __construct($icon, $alt = 'Icono')
    {
        $this->icon = $icon;
        $this->alt = $alt;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.footer-icon');
    }
}