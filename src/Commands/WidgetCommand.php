<?php

namespace Annaba\Admin\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use function Laravel\Prompts\confirm;

class WidgetCommand extends Command
{
    protected $signature = 'annaba:make-widget';
    protected $description = 'Crée un widget Annaba pour un panel';

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

        // 2) Nom du widget : question ouverte
        $name = text(
            label: 'Nom du widget ?',
            placeholder: 'ex: widget1',
            required: true,
            validate: fn (string $v) => preg_match('/^[A-Za-z][A-Za-z0-9_\-]*$/', $v)
                ? null
                : 'Le nom doit commencer par une lettre (lettres, chiffres, - et _ autorisés).',
        );

        // 3) Titre affiché dans le widget
        $title = text(
            label: 'Titre du widget ?',
            placeholder: 'ex: New Customers',
            default: Str::headline($name),
            required: true,
        );

        // 4) Noms dérivés
        $panelStudly = Str::studly($panel);
        $panelKebab  = Str::kebab($panel);
        $class       = Str::studly($name);
        $viewName    = Str::kebab($class);

        $namespace = "App\\Livewire\\Annaba\\{$panelStudly}\\Widgets";
        $view      = "livewire.annaba.{$panelKebab}.widgets.{$viewName}";

        $classPath = app_path("Livewire/Annaba/{$panelStudly}/Widgets/{$class}.php");
        $viewPath  = resource_path("views/livewire/annaba/{$panelKebab}/widgets/{$viewName}.blade.php");

        // 5) Sécurité : fichiers déjà existants
        foreach ([$classPath, $viewPath] as $path) {
            if (File::exists($path) && ! confirm("Le fichier existe déjà : {$path}. L'écraser ?", false)) {
                $this->warn('Opération annulée.');
                return self::FAILURE;
            }
        }

        // 6) Génération
        File::ensureDirectoryExists(dirname($classPath));
        File::ensureDirectoryExists(dirname($viewPath));

        $replace = [
            '%%NAMESPACE%%' => $namespace,
            '%%CLASS%%'     => $class,
            '%%VIEW%%'      => $view,
            '%%TITLE%%'     => addslashes($title),
        ];

        File::put($classPath, strtr($this->classStub(), $replace));
        File::put($viewPath, strtr($this->viewStub(), $replace));

        $this->info("Widget créé pour le panel « {$panel} » :");
        $this->line("  - {$classPath}");
        $this->line("  - {$viewPath}");
        $this->line("Utilisation : <livewire:annaba.{$panelKebab}.widgets.{$viewName} />");

        return self::SUCCESS;
    }

    protected function classStub(): string
    {
        return <<<'STUB'
<?php

namespace %%NAMESPACE%%;

use Livewire\Component;

class %%CLASS%% extends Component
{
    public $title;
    public $value;
    public $icon = 'account_circle';

    public function mount()
    {
        $this->title = '%%TITLE%%';
        $this->value = '42K';
    }

    public function updateValue()
    {
        $this->value = '46K';
    }

    public function render()
    {
        return view('%%VIEW%%');
    }
}
STUB;
    }

    protected function viewStub(): string
    {
        return <<<'STUB'
<div class="pb-[15px] pt-[15px]
 bg-[#eee] flex
 items-center justify-center">

    <div>
        <div class="flex justify-center text-[darkblue]">
            <span class="block pr-[3px]">
                {{ $title }}
            </span>
            <span class="material-icons block text-[blue]">
                {{ $icon }}
            </span>
        </div>

        <div class="text-center font-bold text-[24px]">
            {{ $value }}
        </div>

        <div class="text-center mt-[5px]">
            <button class="text-black border-[1px] border-[black] rounded-[3px] w-[140px] p-[10px]"
                wire:click="updateValue()">
                Update
            </button>
        </div>
    </div>

</div>
STUB;
    }
}