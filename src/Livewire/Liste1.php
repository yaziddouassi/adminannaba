<?php

namespace Annaba\Admin\Livewire;

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

use Livewire\Component;
use Illuminate\Support\Facades\File;
use Annaba\Admin\Crud\AnnabaListing;
use Annaba\Admin\Fields\TextInput;
use Annaba\Admin\Fields\FileUpload;
use Annaba\Admin\Fields\RichEditor;
use Annaba\Admin\Fields\Select;
use Annaba\Admin\Fields\Password;
use Annaba\Admin\Fields\CheckboxList;
use Annaba\Admin\Fields\Radio;
use Annaba\Admin\Fields\Checkbox;

class Liste1 extends AnnabaListing
{

    public $search = '';
    public $model = '\App\Models\Post';

    // Réinitialise la pagination quand on tape dans la recherche
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->urlStorage = config('annabadmin.storage_url');
         $this->initAll();
         $this->backUp();
      
         
    }

    public function initAll() {
       
         $this->addForm([
            'action' => 'update1',
        ])->form([
            RichEditor::make('name')->value('<p>Hello</p>'),
        ])->onUpdate()
          ->btnFermer();

        $this->addForm([
            'action' => 'create',
        ])->form([
            Checkbox::make('is_active')
  ->value(false),
            RichEditor::make('name')->value('<p>Hello</p>'),
        ])->btnFermer();

        $this->addFilter('id','Id');
        $this->addFilter('name','Nom');

        $this->addBulk([
        'action' => 'bulk1',
        'label' => 'Ajouter',
        'icon' => 'edit',
        'class' => 'text-[red]',
        'confirmation' => 'confirm1 ?',
        'message' => 'Records changed'
        ]);

        $this->addBulk([
        'action' => 'bulk2',
        'label' => 'Modifier',
        'icon' => 'description',
        'class' => 'text-[blue]',
        'confirmation' => 'confirm2',
        'message' => 'Records changed'
        ]);
         
      
        // dd($this->annabaFormList);
       }

    public function bulk1()
    {
        dd($this->tabIds);
    }

     public function bulk2()
    {
        dd($this->tabIds);
    }

    public function create()
    {


       $validated = $this->validate([ 
           $this->inputName('create','name')  => ['required'],
        ],[],
        [$this->inputName('create' , 'name')  => 'name']); 

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
        notyf.success("Post créé avec succès !");
       JS);   
    }


     public function update1()
    {

      
        $validated = $this->validate([ 
           $this->inputName('update1','name')  => ['required'],
        ],[],
        [$this->inputName('update1' , 'name')  => 'name']); 

         $this->record = $this->model::find($this->getIde('update1'));

         if($this->record) {
             $this->update('update1');
        
             $this->record->save(); 
             $this->initRecord('update1',$this->record);
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
        $query = $this->model::query();

        if ($this->search !== '') {
            $query->where('name', 'like', '%' . $this->search . '%');
        }


        foreach ($this->filterActifs as $key => $value) {
            $query->orderBy($key, $value) ;
        }

        $entitys = $query->paginate(1);

        return view('adminannaba::livewire.liste1', [
            'entitys' => $entitys,
        ]);
    }
}        
