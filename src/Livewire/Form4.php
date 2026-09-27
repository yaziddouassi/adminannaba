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

class Form4 extends AnnabaForm
{

   
    public $model = '\App\Models\Article';
    public $ide ;

    public function mount($record)
    {
        $this->urlStorage = config('annabadmin.storage_url');
         $this->initAll();
         $this->backUp();
         $this->ide = $record->id ;
         $this->initFields('update1',$record);
         
    }

    public function initAll() {
       
       
           $this->addForm([
            'action' => 'update1',
        ])->form([
            TextInput::make('name'),
            TextInput::make('city'),
        ])->onWizardUpdate([
             'wizardCount' => 2,
             'wizardForm' => [1 => ['name'], 2 => ['city']],
             'wizardLabel' => [1 => 'first', 2 => 'second'],
             'wizardStop' => [],
           ]);

       } 


    public function update1()
    {

        if($this->getWizardCurrent('update1') == 1) {
            
             $validated = $this->validate([ 
               $this->inputName('update1','name')  => ['required'],
             ],[],
             [$this->inputName('update1' , 'name')  => 'name']);

             $this->nextStep('update1');
            
         }

         if($this->getWizardCurrent('update1') == 2) {
             $validated = $this->validate([ 
               $this->inputName('update1','city')  => ['required'],
             ],[],
             [$this->inputName('update1' , 'city')  => 'city']);

         }


          if($this->getWizardAction('update1') == 'valider') {
           
            $this->record = $this->model::find($this->getIde('update1'));

            if($this->record) {
                 $this->update('update1');
                 $this->record->save(); 
                 $this->initFields('update1',$this->record);
              }

             $this->js(<<<'JS'
             const notyf = new Notyf({
             position: {
               x: 'right',
               y: 'top',
            },
            });
            notyf.success("Edité avec succès !");
            JS);   
         }

      
    }



    public function render()
    {
        return view('adminannaba::livewire.form4');
    }
}        
