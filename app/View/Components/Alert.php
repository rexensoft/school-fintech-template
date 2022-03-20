<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    public $color;
    public $value;


    public function __construct($color, $value)
    {
        $this->color = $color;
        $this->color = $this->getColor();
        $this->value = $value;
    }


    public function render()
    {
        return view('components.alert');
    }

    
    public function getColor() {
        $color  = strtolower($this->color);
        $colors = ['danger', 'info', 'secondary', 'warning'];
        $result = '';

        foreach($colors as $item)
            if($item === $color) $result = $item;

        return $result;
    }
}
