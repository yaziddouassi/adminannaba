<?php

namespace Annaba\Admin\Livewire;
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
use Livewire\Component;
use Illuminate\Support\Facades\File;
use Annaba\Admin\Crud\AnnabaForm;
use Annaba\Admin\Fields\TextInput;
use Annaba\Admin\Fields\DateInput;
use Annaba\Admin\Fields\Number;

class Adminannaba2 extends Component
{
    public $record;

    public function mount() {
       
       $this->record = \App\Models\Post::first();
        
       }

    
     public function render()
    {
        return view('adminannaba::livewire.adminannaba2')
                  ->layout('adminannaba::layouts.app');
    } 
   
}