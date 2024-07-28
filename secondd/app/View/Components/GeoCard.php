<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class GeoCard extends Component
{
    /**
     * Create a new component instance.
     * 
     * @param \App\Models\GeoObject[] $geoObjects
     */
    public function __construct(
        public $geoObjects
    ){}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.geo-card', ["geoObjects"=>$this->geoObjects]);
    }
}
