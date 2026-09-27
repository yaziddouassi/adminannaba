<?php

namespace Annaba\Admin\Livewire;

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

use Livewire\Component;
use Illuminate\Support\Facades\File;
use Annaba\Admin\Crud\AnnabaForm;
use Annaba\Admin\Fields\TextInput;
use Annaba\Admin\Fields\FileUpload;
use Annaba\Admin\Fields\RichEditor;
use Annaba\Admin\Fields\Select;
use Annaba\Admin\Fields\Password;
use Annaba\Admin\Fields\CheckboxList;
use Annaba\Admin\Fields\Radio;
use Annaba\Admin\Fields\Checkbox;

class Form3 extends AnnabaForm
{

   
    public $model = '\App\Models\Article';

    public function mount()
    {
        $this->urlStorage = config('annabadmin.storage_url');
         $this->initAll();
         $this->backUp();
    }

    public function initAll() {
       
         $this->addForm([
            'action' => 'create',
        ])->form([
            TextInput::make('name'),
            TextInput::make('city'),
        ])->onWizard([
             'wizardCount' => 2,
             'wizardForm' => [1 => ['name'], 2 => ['city']],
             'wizardLabel' => [1 => 'first', 2 => 'second'],
             'wizardStop' => [],
           ]);
        
       } 


    public function create()
    {

       if($this->getWizardCurrent('create') == 1) {
            
             $validated = $this->validate([ 
               $this->inputName('create','name')  => ['required'],
             ],[],
             [$this->inputName('create' , 'name')  => 'name']);

             $this->nextStep('create');
            
         }

         if($this->getWizardCurrent('create') == 2) {
             $validated = $this->validate([ 
               $this->inputName('create','city')  => ['required'],
             ],[],
             [$this->inputName('create' , 'city')  => 'city']);

         }


          if($this->getWizardAction('create') == 'valider') {
           
            $this->record = new $this->model;
            $this->insert('create');
            $this->record->save(); 
            $this->resetForm('create');

             $this->js(<<<'JS'
             const notyf = new Notyf({
             position: {
               x: 'right',
               y: 'top',
            },
             });
             notyf.success("Sauvegardé avec succès !");
            JS);   
         }
       
    }



    public function render()
    {
        return view('adminannaba::livewire.form3');
    }
}        
