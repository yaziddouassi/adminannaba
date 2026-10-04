<?php

namespace Annaba\Admin\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use function Laravel\Prompts\confirm;

class ListeCommand extends Command
{
    protected $signature = 'annaba:make-liste';
    protected $description = 'Crée une liste Annaba (simple ou wizard) pour un panel';

    public function handle(): int
    {
        // 1) Panel : choix dans le config
        $panels = config('annabadmin.panels', []);

        if (empty($panels)) {
            $this->error("Aucun panel défini dans config/annabadmin.php ('panels').");
            return self::FAILURE;
        }

        $panel = select(
            label: 'Pour quel panel ?',
            options: $panels,
            default: $panels[0],
        );

        // 2) Nom de la liste : question ouverte
        $name = text(
            label: 'Nom de la liste ?',
            placeholder: 'ex: liste1',
            required: true,
            validate: fn (string $v) => preg_match('/^[A-Za-z][A-Za-z0-9_\-]*$/', $v)
                ? null
                : 'Le nom doit commencer par une lettre (lettres, chiffres, - et _ autorisés).',
        );

        // 3) Type de liste
        $type = select(
            label: 'Type de liste ?',
            options: ['simple' => 'Simple', 'wizard' => 'Wizard (formulaire en étapes)'],
            default: 'simple',
        );

        // 4) Modèle
        $model = Str::studly(text(
            label: 'Nom du modèle ?',
            placeholder: 'ex: Post',
            default: 'Post',
            required: true,
        ));

        // 5) Noms dérivés
        $panelStudly = Str::studly($panel);
        $panelKebab  = Str::kebab($panel);
        $class       = Str::studly($name);
        $viewName    = Str::kebab($class);

        $namespace = "App\\Livewire\\Annaba\\{$panelStudly}\\Listes";
        $view      = "livewire.annaba.{$panelKebab}.listes.{$viewName}";

        $classPath = app_path("Livewire/Annaba/{$panelStudly}/Listes/{$class}.php");
        $viewPath  = resource_path("views/livewire/annaba/{$panelKebab}/listes/{$viewName}.blade.php");

        // 6) Sécurité : fichiers déjà existants
        foreach ([$classPath, $viewPath] as $path) {
            if (File::exists($path) && ! confirm("Le fichier existe déjà : {$path}. L'écraser ?", false)) {
                $this->warn('Opération annulée.');
                return self::FAILURE;
            }
        }

        // 7) Génération
        File::ensureDirectoryExists(dirname($classPath));
        File::ensureDirectoryExists(dirname($viewPath));

        $replace = [
            '%%NAMESPACE%%' => $namespace,
            '%%CLASS%%'     => $class,
            '%%VIEW%%'      => $view,
            '%%MODEL%%'     => $model,
            '%%LABEL%%'     => Str::plural($model),
        ];

        $classStub = $type === 'wizard' ? $this->wizardStub() : $this->simpleStub();

        File::put($classPath, strtr($classStub, $replace));
        File::put($viewPath, strtr($this->viewStub(), $replace));

        $this->info("Liste {$type} créée pour le panel « {$panel} » :");
        $this->line("  - {$classPath}");
        $this->line("  - {$viewPath}");
        $this->line("Utilisation : <livewire:annaba.{$panelKebab}.listes.{$viewName} />");

        return self::SUCCESS;
    }

    /* ------------------------------------------------------------------ */
    /*  Parties communes aux classes                                       */
    /* ------------------------------------------------------------------ */

    protected function classHead(): string
    {
        return <<<'STUB'
<?php

namespace %%NAMESPACE%%;

use Livewire\Component;
use Illuminate\Support\Facades\File;
use Annaba\Admin\Crud\AnnabaListing;
use Annaba\Admin\Fields\TextInput;

class %%CLASS%% extends AnnabaListing
{
    public $search = '';
    public $model = '\App\Models\%%MODEL%%';

    // Réinitialise la pagination quand on tape dans la recherche
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->urlStorage = config('adminannaba.storage_url');
        $this->initAll();
        $this->backUp();
    }

STUB;
    }

    protected function classTail(): string
    {
        return <<<'STUB'

    public function bulk1()
    {
        dd($this->tabIds);
    }

    public function deleteById($ide)
    {
        $this->model::findOrFail($ide)->delete();

        $this->js(<<<'JS'
            const notyf = new Notyf({
                position: { x: 'right', y: 'top' },
            });
            notyf.success("Supprimé avec succès !");
        JS);
    }

    public function render()
    {
        $query = $this->model::query();

        if ($this->search !== '') {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        foreach ($this->filterActifs as $key => $value) {
            $query->orderBy($key, $value);
        }

        $entitys = $query->paginate(10);

        return view('%%VIEW%%', [
            'entitys' => $entitys,
            'selectedRecords' => $this->annabaSelectedRecords(),
        ]);
    }
}
STUB;
    }

    /* ------------------------------------------------------------------ */
    /*  Liste simple                                                       */
    /* ------------------------------------------------------------------ */

    protected function simpleStub(): string
    {
        return $this->classHead() . <<<'STUB'
    public function initAll()
    {
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

        $this->addFilter('id', 'Id');

        $this->addBulk([
            'action' => 'bulk1',
            'label' => 'Ajouter',
            'icon' => 'edit',
            'class' => 'text-[red]',
            'confirmation' => 'confirm1 ?',
            'message' => 'Records changed',
        ]);
    }

    public function create()
    {
        $validated = $this->validate([
            $this->inputName('create', 'name') => ['required'],
        ], [], [$this->inputName('create', 'name') => 'name']);

        $this->record = new $this->model;
        $this->insert('create');
        $this->record->save();

        $this->resetForm('create');
        $this->js(<<<'JS'
            const notyf = new Notyf({
                position: { x: 'right', y: 'top' },
            });
            notyf.success("Créé avec succès !");
        JS);
    }

    public function update1()
    {
        $validated = $this->validate([
            $this->inputName('update1', 'name') => ['required'],
        ], [], [$this->inputName('update1', 'name') => 'name']);

        $this->record = $this->model::find($this->getIde('update1'));

        if ($this->record) {
            $this->update('update1');
            $this->record->save();
            $this->initRecord('update1', $this->record);
        }

        $this->js(<<<'JS'
            const notyf = new Notyf({
                position: { x: 'right', y: 'top' },
            });
            notyf.success("Édité avec succès !");
        JS);
    }
STUB . $this->classTail();
    }

    /* ------------------------------------------------------------------ */
    /*  Liste wizard                                                       */
    /* ------------------------------------------------------------------ */

    protected function wizardStub(): string
    {
        return $this->classHead() . <<<'STUB'
    public function initAll()
    {
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
        ])->btnFermer();

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
        ])->btnFermer();

        $this->addFilter('id', 'Id');

        $this->addBulk([
            'action' => 'bulk1',
            'label' => 'Ajouter',
            'icon' => 'edit',
            'class' => 'text-[red]',
            'confirmation' => 'confirm1 ?',
            'message' => 'Records changed',
        ]);
    }

    public function create()
    {
        if ($this->getWizardCurrent('create') == 1) {
            $validated = $this->validate([
                $this->inputName('create', 'name') => ['required'],
            ], [], [$this->inputName('create', 'name') => 'name']);

            $this->nextStep('create');
        }

        if ($this->getWizardCurrent('create') == 2) {
            $validated = $this->validate([
                $this->inputName('create', 'city') => ['required'],
            ], [], [$this->inputName('create', 'city') => 'city']);
        }

        if ($this->getWizardAction('create') == 'valider') {
            $this->record = new $this->model;
            $this->insert('create');
            $this->record->save();
            $this->resetForm('create');

            $this->js(<<<'JS'
                const notyf = new Notyf({
                    position: { x: 'right', y: 'top' },
                });
                notyf.success("Sauvegardé avec succès !");
            JS);
        }
    }

    public function update1()
    {
        if ($this->getWizardCurrent('update1') == 1) {
            $validated = $this->validate([
                $this->inputName('update1', 'name') => ['required'],
            ], [], [$this->inputName('update1', 'name') => 'name']);

            $this->nextStep('update1');
        }

        if ($this->getWizardCurrent('update1') == 2) {
            $validated = $this->validate([
                $this->inputName('update1', 'city') => ['required'],
            ], [], [$this->inputName('update1', 'city') => 'city']);
        }

        if ($this->getWizardAction('update1') == 'valider') {
            $this->record = $this->model::find($this->getIde('update1'));

            if ($this->record) {
                $this->update('update1');
                $this->record->save();
                $this->initRecord('update1', $this->record);
            }

            $this->closeModal();

            $this->js(<<<'JS'
                const notyf = new Notyf({
                    position: { x: 'right', y: 'top' },
                });
                notyf.success("Édité avec succès !");
            JS);
        }
    }
STUB . $this->classTail();
    }

    /* ------------------------------------------------------------------ */
    /*  Vue (identique pour simple et wizard)                              */
    /* ------------------------------------------------------------------ */

    protected function viewStub(): string
    {
        return <<<'STUB'
<div class="w-full overflow-x-auto">

    <div class="pb-[10px] text-right">
        @include('adminannaba::composants.btnModal',
            ['form' => 'create', 'label' => '%%LABEL%%'])
    </div>

    <div class="h-[50px] border-b-[1px] border-b-gray-300 flex">
        <div class="w-full">
            @include('adminannaba::composants.groupActions')
        </div>
        <div class="min-w-[310px] max-w-[310px] flex">
            @include('adminannaba::composants.filters')
            @include('adminannaba::composants.search')
        </div>
    </div>

    <div>
        <table class="w-full min-w-[640px] text-left text-sm text-gray-700">
            <thead>
                <tr>
                    <th scope="col" class="px-4 py-3 text-center font-semibold border-b"></th>
                    <th scope="col" class="px-4 py-3 text-center font-semibold border-b">ID</th>
                    <th scope="col" class="px-4 py-3 text-center font-semibold border-b">Nom</th>
                    <th scope="col" class="px-4 py-3 text-center font-semibold border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entitys as $entity)
                <tr class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-[5px] py-3 text-center">
                        <span @click=" if ($wire.tabIds.includes({{ $entity->id }})) { $wire.tabIds = $wire.tabIds.filter( id => id !== {{ $entity->id }} ) } else { $wire.tabIds.push({{ $entity->id }}) } ">
                            <template x-if="$wire.tabIds.includes({{ $entity->id }})">
                                @include('adminannaba::composants.svg2')
                            </template>
                            <template x-if="!$wire.tabIds.includes({{ $entity->id }})">
                                @include('adminannaba::composants.svg1')
                            </template>
                        </span>
                    </td>
                    <td class="px-[5px] py-3 text-center">{{ $entity->id }}</td>
                    <td class="px-[5px] py-3 text-center">{{ $entity->name }}</td>
                    <td class="px-[5px] py-3 text-center">
                        <div class="flex gap-[5px] justify-center">
                            @include('adminannaba::composants.btnDeleteById',
                                ['ide' => $entity->id,
                                 'message' => 'Êtes-vous sûr de vouloir supprimer cet élément ?'])
                            @include('adminannaba::composants.btnModal2',
                                ['form' => 'update1', 'label' => 'Edit', 'icon' => 'edit',
                                 'record' => $entity,
                                 'class' => 'bg-[green] text-white p-[11px] rounded-[6px]'])
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>
        @include('adminannaba::modalForm')
    </div>

    <div>
        {{ $entitys->links() }}
    </div>

</div>
STUB;
    }
}