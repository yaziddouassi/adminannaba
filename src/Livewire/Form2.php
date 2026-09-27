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

class Form2 extends AnnabaForm
{

   
    public $model = '\App\Models\Post';
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
        ])->onUpdate();
        
       } 


    public function update1()
    {

      $validated = $this->validate([ 
           $this->inputName('update1','name')  => ['required'],
        ],[],
        [$this->inputName('update1' , 'name')  => 'name']); 

         $this->record = $this->model::find($this->ide);

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
        notyf.success("Post édtité avec succès !");
       JS);   

      
    }



    public function render()
    {
        return view('adminannaba::livewire.form2');
    }
}        
