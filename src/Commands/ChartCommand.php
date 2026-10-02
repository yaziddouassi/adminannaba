<?php

namespace Annaba\Admin\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use function Laravel\Prompts\confirm;

class ChartCommand extends Command
{
    protected $signature = 'annaba:make-chart';
    protected $description = 'Crée un chart Annaba (Chart.js) pour un panel';

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

        // 2) Nom du chart : question ouverte
        $name = text(
            label: 'Nom du chart ?',
            placeholder: 'ex: chart1',
            required: true,
            validate: fn (string $v) => preg_match('/^[A-Za-z][A-Za-z0-9_\-]*$/', $v)
                ? null
                : 'Le nom doit commencer par une lettre (lettres, chiffres, - et _ autorisés).',
        );

        // 3) Type de graphique
        $type = select(
            label: 'Type de graphique ?',
            options: [
                'pie'       => 'Pie',
                'doughnut'  => 'Doughnut',
                'bar'       => 'Bar',
                'line'      => 'Line',
                'polarArea' => 'Polar area',
            ],
            default: 'pie',
        );

        // 4) Noms dérivés
        $panelStudly = Str::studly($panel);
        $panelKebab  = Str::kebab($panel);
        $class       = Str::studly($name);
        $viewName    = Str::kebab($class);

        $namespace = "App\\Livewire\\Annaba\\{$panelStudly}\\Charts";
        $view      = "livewire.annaba.{$panelKebab}.charts.{$viewName}";

        $classPath = app_path("Livewire/Annaba/{$panelStudly}/Charts/{$class}.php");
        $viewPath  = resource_path("views/livewire/annaba/{$panelKebab}/charts/{$viewName}.blade.php");

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
            '%%TYPE%%'      => $type,
            '%%CHART_ID%%'  => 'Chart' . $class,
            '%%LABEL%%'     => Str::headline($class),
        ];

        File::put($classPath, strtr($this->classStub(), $replace));
        File::put($viewPath, strtr($this->viewStub(), $replace));

        $this->info("Chart « {$type} » créé pour le panel « {$panel} » :");
        $this->line("  - {$classPath}");
        $this->line("  - {$viewPath}");
        $this->line("Utilisation : <livewire:annaba.{$panelKebab}.charts.{$viewName} />");

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
    public $data = [];
    public $labels = [];
    public $label;
    public $chartId;
    public $chartType;
    public $backgroundColor = [];

    public function mount()
    {
        $this->chartId = '%%CHART_ID%%';
        $this->label = '%%LABEL%%';
        $this->data = [1, 2, 3, 8];
        $this->labels = ['first', 'second', 'third', 'four'];
        $this->chartType = '%%TYPE%%'; // line bar doughnut polarArea pie
        $this->backgroundColor = [
            'blue',
            'red',
            'black',
            'lime',
        ];
    }

    public function chartchange()
    {
        $this->data = [1, 2, 3];
        $this->labels = ['first', 'second', 'third'];
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
<div class="pb-[10px] bg-[#eee] min-h-[calc(50vh-46px)] flex items-center"
x-data="{
    data: $wire.entangle('data'),
    labels: $wire.entangle('labels'),
    chart: null,

    init() {
        this.chart = this.newchart(this.$refs.canvas, this.labels, this.data);
    },

    changechart() {
        this.chart.destroy();
        $wire.chartchange().then(() => {
            this.chart = this.newchart(this.$refs.canvas, this.labels, this.data);
        });
    },

    newchart(element, labels, data) {
        return new Chart(element, {
            type: $wire.chartType,
            data: {
                labels: labels,
                datasets: [{
                    label: $wire.label,
                    data: data,
                    backgroundColor: [...$wire.backgroundColor],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    },
}">

    <div class="w-full">
        <div style="position: relative; width: 100%; padding-top: 85%;">
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; max-height: none;">
                <canvas wire:ignore x-ref="canvas"></canvas>
            </div>
        </div>

        <div class="text-center mt-[10px]">
            <button @click="changechart" class="border-[1px] border-black p-[8px]">
                Update
            </button>
        </div>
    </div>

</div>
STUB;
    }
}