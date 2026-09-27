<?php

namespace Annaba\Admin\Livewire;
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
use Livewire\Component;
use Illuminate\Support\Facades\File;

class Widget1 extends Component
{
    public $title ;
    public $value ;
    public $icon  = 'account_circle' ;

    public function mount()
    {
       $this->title = 'New Customers' ;
       $this->value = '42K' ;
    }

    public function updateValue()
    {
        $this->value = '46K' ;
    }

    public function render()
    {
        return view('adminannaba::livewire.widget1');
    }
}