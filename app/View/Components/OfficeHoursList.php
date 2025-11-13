<?php

namespace App\View\Components;

use Illuminate\View\Component;

class OfficeHoursList extends Component
{
    public $officeHours;

    public function __construct($officeHours = [])
    {
        $this->officeHours = $officeHours;
    }

    public function render()
    {
        return view('components.office-hours-list');
    }
}
