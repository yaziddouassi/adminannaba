<?php

namespace Annaba\Admin\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use function Laravel\Prompts\confirm;

class FormCommand extends Command
{
    protected $signature = 'annaba:make-form';
    protected $description = 'Crée un formulaire Annaba (simple, simple update, wizard, wizard update)';

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

        // 2) Nom du formulaire : question ouverte
        $name = text(
            label: 'Nom du formulaire ?',
            placeholder: 'ex: form1',
            required: true,
            validate: fn (string $v) => preg_match('/^[A-Za-z][A-Za-z0-9_\-]*$/', $v)
                ? null
                : 'Le nom doit commencer par une lettre (lettres, chiffres, - et _ autorisés).',
        );

        // 3) Type de formulaire
        $type = select(
            label: 'Type de formulaire ?',
            options: [
                'simple'        => 'Form simple',
                'simple-update' => 'Form simple update',
                'wizard'        => 'Form wizard',
                'wizard-update' => 'Form wizard update',
            ],
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

        $namespace = "App\\Livewire\\Annaba\\{$panelStudly}\\Forms";
        $view      = "livewire.annaba.{$panelKebab}.forms.{$viewName}";

        $classPath = app_path("Livewire/Annaba/{$panelStudly}/Forms/{$class}.php");
        $viewPath  = resource_path("views/livewire/annaba/{$panelKebab}/forms/{$viewName}.blade.php");

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
        ];

        [$classStub, $viewStub] = match ($type) {
            'simple'        => [$this->simpleStub(),       $this->simpleView()],
            'simple-update' => [$this->simpleUpdateStub(), $this->simpleUpdateView()],
            'wizard'        => [$this->wizardStub(),       $this->wizardView()],
            'wizard-update' => [$this->wizardUpdateStub(), $this->wizardUpdateView()],
        };

        File::put($classPath, strtr($classStub, $replace));
        File::put($viewPath, strtr($viewStub, $replace));

        $this->info("Formulaire « {$type} » créé pour le panel « {$panel} » :");
        $this->line("  - {$classPath}");
        $this->line("  - {$viewPath}");

        if (str_ends_with($type, 'update')) {
            $this->line("Utilisation : <livewire:annaba.{$panelKebab}.forms.{$viewName} :record=\"\$record\" />");
        } else {
            $this->line("Utilisation : <livewire:annaba.{$panelKebab}.forms.{$viewName} />");
        }

        return self::SUCCESS;
    }

    /* ------------------------------------------------------------------ */
    /*  Morceaux communs aux classes                                       */
    /* ------------------------------------------------------------------ */

    protected function head(bool $update): string
    {
        $props = $update
            ? "    public \$model = '\\App\\Models\\%%MODEL%%';\n    public \$ide;\n\n"
            : "    public \$model = '\\App\\Models\\%%MODEL%%';\n\n";

        $mount = $update
            ? <<<'M'
    public function mount($record)
    {
        $this->urlStorage = config('annabadmin.storage_url');
        $this->initAll();
        $this->backUp();
        $this->ide = $record->id;
        $this->initFields('update1', $record);
    }

M
            : <<<'M'
    public function mount()
    {
        $this->urlStorage = config('annabadmin.storage_url');
        $this->initAll();
        $this->backUp();
    }

M;

        $top = <<<'H'
<?php

namespace %%NAMESPACE%%;

use Livewire\Component;
use Illuminate\Support\Facades\File;
use Annaba\Admin\Crud\AnnabaForm;
use Annaba\Admin\Fields\TextInput;

class %%CLASS%% extends AnnabaForm
{

H;

        return $top . $props . $mount . "\n";
    }

    protected function tail(): string
    {
        return <<<'T'

    public function render()
    {
        return view('%%VIEW%%', [
            'selectedRecords' => $this->annabaSelectedRecords(),
        ]);
    }
}
T;
    }

    /* ------------------------------------------------------------------ */
    /*  Classes                                                            */
    /* ------------------------------------------------------------------ */

    protected function simpleStub(): string
    {
        return $this->head(false) . <<<'STUB'
    public function initAll()
    {
        $this->addForm([
            'action' => 'create',
        ])->form([
            TextInput::make('name'),
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
STUB . $this->tail();
    }

    protected function simpleUpdateStub(): string
    {
        return $this->head(true) . <<<'STUB'
    public function initAll()
    {
        $this->addForm([
            'action' => 'update1',
        ])->form([
            TextInput::make('name'),
        ])->onUpdate();
    }

    public function update1()
    {
        $validated = $this->validate([
            $this->inputName('update1', 'name') => ['required'],
        ], [], [$this->inputName('update1', 'name') => 'name']);

        $this->record = $this->model::find($this->ide);

        if ($this->record) {
            $this->update('update1');
            $this->record->save();
            $this->initFields('update1', $this->record);
        }

        $this->js(<<<'JS'
            const notyf = new Notyf({
                position: { x: 'right', y: 'top' },
            });
            notyf.success("Édité avec succès !");
        JS);
    }
STUB . $this->tail();
    }

    protected function wizardStub(): string
    {
        return $this->head(false) . <<<'STUB'
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
STUB . $this->tail();
    }

    protected function wizardUpdateStub(): string
    {
        return $this->head(true) . <<<'STUB'
    public function initAll()
    {
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
            $this->record = $this->model::find($this->ide);

            if ($this->record) {
                $this->update('update1');
                $this->record->save();
                $this->initFields('update1', $this->record);
            }

            $this->js(<<<'JS'
                const notyf = new Notyf({
                    position: { x: 'right', y: 'top' },
                });
                notyf.success("Édité avec succès !");
            JS);
        }
    }
STUB . $this->tail();
    }

    /* ------------------------------------------------------------------ */
    /*  Vues                                                               */
    /* ------------------------------------------------------------------ */

    protected function simpleView(): string
    {
        return <<<'STUB'
<div>
    <div class="p-[10px]">
        <div>
            @include('adminannaba::fields.inputText',
                ['form' => 'create', 'field' => 'name'])
        </div>

        @include('adminannaba::composants.forms.btnCreate',
            ['form' => 'create'])
    </div>
</div>
STUB;
    }

    protected function simpleUpdateView(): string
    {
        return <<<'STUB'
<div>
    <div class="p-[10px]">
        <div>
            @include('adminannaba::fields.inputText',
                ['form' => 'update1', 'field' => 'name'])
        </div>

        @include('adminannaba::composants.forms.btnUpdate',
            ['form' => 'update1'])
    </div>
</div>
STUB;
    }

    protected function wizardView(): string
    {
        return <<<'STUB'
<div>
    <div>
        @include('adminannaba::composants.wizard.wizardStep2',
            ['form' => 'create'])
    </div>

    <div class="p-[10px]">
        <div>
            @if($annabaFormList['create']['info']['wizardCurrent'] == 1)
                @include('adminannaba::fields.inputText',
                    ['form' => 'create', 'field' => 'name'])
            @endif

            @if($annabaFormList['create']['info']['wizardCurrent'] == 2)
                @include('adminannaba::fields.inputText',
                    ['form' => 'create', 'field' => 'city'])
            @endif
        </div>

        @include('adminannaba::composants.forms.btnWizard',
            ['form' => 'create'])
    </div>
</div>
STUB;
    }

    protected function wizardUpdateView(): string
    {
        return <<<'STUB'
<div>
    <div>
        @include('adminannaba::composants.wizard.wizardStep2',
            ['form' => 'update1'])
    </div>

    <div class="p-[10px]">
        <div>
            @if($annabaFormList['update1']['info']['wizardCurrent'] == 1)
                @include('adminannaba::fields.inputText',
                    ['form' => 'update1', 'field' => 'name'])
            @endif

            @if($annabaFormList['update1']['info']['wizardCurrent'] == 2)
                @include('adminannaba::fields.inputText',
                    ['form' => 'update1', 'field' => 'city'])
            @endif
        </div>

        @include('adminannaba::composants.forms.btnWizardUpdate',
            ['form' => 'update1'])
    </div>
</div>
STUB;
    }
}