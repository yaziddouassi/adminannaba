<?php

namespace Annaba\Admin\Livewire;
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
use Livewire\Component;
use Illuminate\Support\Facades\File;


class Adminannaba3 extends Component
{

     public function render()
    {
        return view('adminannaba::livewire.adminannaba3')
                   ->layout('adminannaba::layouts.app');
    } 
   
}