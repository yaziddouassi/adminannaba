<?php

namespace Annaba\Admin\Livewire;

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

use Livewire\Component;
use Illuminate\Support\Facades\File;
use Annaba\Admin\Crud\AnnabaListing;
use Annaba\Admin\Fields\TextInput;

class Liste1 extends AnnabaListing
{

    public $search = '';
    public $model = '\App\Models\Post';

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
            TextInput::make('name'),
        ])->onUpdate()
          ->btnFermer();

        $this->addForm([
            'action' => 'create',
        ])->form([
            TextInput::make('name'),
        ])->btnFermer();

        $this->addFilter('id','Id');

        $this->addBulk([
        'action' => 'bulk1',
        'label' => 'Ajouter',
        'icon' => 'edit',
        'class' => 'text-[red]',
        'confirmation' => 'confirm1 ?',
        'message' => 'Records changed'
        ]);

     
       }

    public function bulk1()
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


    public function deleteById($ide) {

        $this->model::findOrFail($ide)->delete();
        $this->js(<<<'JS'
        const notyf = new Notyf({
           position: {
               x: 'right',
               y: 'top',
            },
        });
        notyf.success("Post supprimé avec succés!");
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

        $entitys = $query->paginate(10);

        return view('adminannaba::livewire.liste1', [
            'entitys' => $entitys,
        ]);
    }
}        
