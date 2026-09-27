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

class Adminannaba1 extends AnnabaListing
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
        $this->addForm([
            'action' => 'update1',
        ])->form([
            TextInput::make('name')
                ->value('wesh'),
            Number::make('price')
                ->value(7)
                ->step(0.2)
                ->max(9)
                ->min(5),
            DateInput::make('birthday')
                ->value('2024-11-12')
                ->label('My Date')
                ->min('2023-11-12')
                ->max('2025-11-12')
        ])->onUpdate();

        $this->addForm([
            'action' => 'create',
        ])->form([
            TextInput::make('name'),
            TextInput::make('price'),
        ]);
    }

    public function create()
    {
        $validated = $this->validate([
            'annabaFormList.create.fields.name.value' => ['required'],
        ]);
    }

    public function render()
    {
        $query = $this->model::query();

        // Recherche LIKE sur le champ name si $search n'est pas vide
        if ($this->search !== '') {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $entitys = $query->paginate(1); // ← Pagination (10 par page)

        return view('adminannaba::livewire.adminannaba1', [
            'entitys' => $entitys,
        ])->layout('adminannaba::layouts.app');
    }
}