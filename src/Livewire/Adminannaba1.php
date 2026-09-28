<?php

namespace Annaba\Admin\Livewire;

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

use Livewire\Component;
use Illuminate\Support\Facades\File;
use Annaba\Admin\Crud\AnnabaListing;
use Annaba\Admin\Fields\TextInput;
use Annaba\Admin\Fields\DateInput;
use Annaba\Admin\Fields\Number;

class Adminannaba1 extends Component
{
   
    public function mount()
    {
       
    }

   
    public function render()
    {
       
        return view('adminannaba::livewire.adminannaba1')
        ->layout('adminannaba::layouts.app');
    }
}