<?php

namespace App\View\Components\Sidebar;

use Illuminate\View\Component;

class Divider extends Component
{
    public $mt, $mb;


    public function __construct($mt=0, $mb=0)
    {
        $this->mt = $mt;
        $this->mb = $mb;
    }


    public function render()
    {
        return view('components.sidebar.divider');
    }
}
