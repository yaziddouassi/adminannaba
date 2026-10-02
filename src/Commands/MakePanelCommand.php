<?php

namespace Annaba\Admin\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

use function Laravel\Prompts\multiselect;

/**
 * php artisan annaba:make-panel
 * php artisan annaba:make-panel admin editor --force
 *
 * Emplacement : app/Console/Commands/MakeAnnabaPanel.php
 * (auto-découverte dans Laravel 11/12 ; sinon l'enregistrer dans Kernel/bootstrap).
 */
class MakePanelCommand extends Command
{
    protected $signature = 'annaba:make-panel
                            {panels?* : Noms des panels (sinon question interactive)}
                            {--force : Écraser les fichiers existants}';

    protected $description = 'Crée des panels Annaba (Livewire + vues + routes) à partir de config/annaba.php';

    /** Nom du fichier de config : config/annaba.php */
    protected string $configKey = 'annabadmin';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $available = config("{$this->configKey}.panels", []);

        if (empty($available)) {
            $this->error("Aucun panel trouvé dans config/{$this->configKey}.php (clé 'panels').");
            return self::FAILURE;
        }

        $panels = $this->argument('panels');

        // La question : quels panels créer ?
        if (empty($panels)) {
            $panels = multiselect(
                label: 'Quels panels voulez-vous créer ?',
                options: array_combine($available, $available),
                default: $available,
                required: true,
                hint: 'Espace pour cocher/décocher, Entrée pour valider'
            );
        }

        foreach ($panels as $panel) {
            if (! in_array($panel, $available, true)) {
                $this->warn("Le panel « {$panel} » n'est pas dans le config, ignoré.");
                continue;
            }
            $this->createPanel($panel);
        }

        $this->newLine();
        $this->info('Terminé.');

        return self::SUCCESS;
    }

    protected function createPanel(string $panel): void
    {
        $slug   = Str::slug($panel);            // editor
        $studly = Str::studly($panel);          // Editor
        $title  = Str::title($panel);           // Editor

        $replace = [
            '__STUDLY__'     => $studly,
            '__SLUG__'       => $slug,
            '__TITLE__'      => $title,
            '__MIDDLEWARE__' => $this->middlewareExport(),
        ];

        $this->components->info("Panel : {$slug}");

        $classDir = app_path("Livewire/Annaba/{$studly}");
        $viewDir  = resource_path("views/livewire/annaba/{$slug}");

        foreach (['Dashboard', 'Login', 'Navbar', 'Sidebar'] as $name) {
            $this->write("{$classDir}/{$name}.php", $this->classStub($name), $replace);
        }

        foreach (['dashboard', 'login', 'navbar', 'sidebar'] as $name) {
            $this->write("{$viewDir}/{$name}.blade.php", $this->viewStub($name), $replace);
        }

        $this->addRoutes($slug, $studly);
    }

    protected function write(string $path, string $stub, array $replace): void
    {
        if ($this->files->exists($path) && ! $this->option('force')) {
            $this->components->twoColumnDetail($this->relative($path), '<fg=yellow>existe déjà</>');
            return;
        }

        $this->files->ensureDirectoryExists(dirname($path));
        $this->files->put($path, strtr($stub, $replace));

        $this->components->twoColumnDetail($this->relative($path), '<fg=green>créé</>');
    }

    protected function addRoutes(string $slug, string $studly): void
    {
        $path = base_path('routes/annaba.php');

        if (! $this->files->exists($path)) {
            $this->files->put($path, "<?php\n\nuse Illuminate\\Support\\Facades\\Route;\n");
        }

        $content = $this->files->get($path);

        if (str_contains($content, "Annaba\\{$studly}\\Dashboard::class")) {
            $this->components->twoColumnDetail('routes/annaba.php', '<fg=yellow>routes déjà présentes</>');
            return;
        }

        $middleware = $this->middlewareExport();

        $routes = <<<PHP


// Panel {$slug}
Route::get('/{$slug}/login', \\App\\Livewire\\Annaba\\{$studly}\\Login::class)->middleware('guest');
Route::get('/{$slug}', \\App\\Livewire\\Annaba\\{$studly}\\Dashboard::class)->middleware({$middleware});

PHP;

        $this->files->append($path, $routes);
        $this->components->twoColumnDetail('routes/annaba.php', '<fg=green>routes ajoutées</>');
    }

    /** middlewareList du config → code PHP (ex: ['auth']) */
    protected function middlewareExport(): string
    {
        $list = (array) config("{$this->configKey}.middlewareList", []);

        return '[' . implode(', ', array_map(fn ($m) => "'{$m}'", $list)) . ']';
    }

    protected function relative(string $path): string
    {
        return ltrim(str_replace(base_path(), '', $path), '/\\');
    }

    /* ------------------------------------------------------------------ */
    /*  STUBS CLASSES                                                      */
    /* ------------------------------------------------------------------ */

    protected function classStub(string $name): string
    {
        return match ($name) {
            'Dashboard' => <<<'STUB'
<?php

namespace App\Livewire\Annaba\__STUDLY__;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.annaba.__SLUG__.dashboard')
                ->layout('adminannaba::layouts.app');
    }
}

STUB,
            'Login' => <<<'STUB'
<?php

namespace App\Livewire\Annaba\__STUDLY__;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            return redirect()->intended('/__SLUG__');
        }

        $this->addError('email', 'Identifiants incorrects.');
    }

    public function render()
    {
        return view('livewire.annaba.__SLUG__.login')
                   ->layout('adminannaba::layouts.app');
    }
}

STUB,
            'Navbar', 'Sidebar' => str_replace(['__NAME__', '__VIEW__'], [$name, strtolower($name)], <<<'STUB'
<?php

namespace App\Livewire\Annaba\__STUDLY__;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class __NAME__ extends Component
{
    public function logout()
    {
        Auth::guard('web')->logout();

        session()->invalidate();
        session()->regenerateToken();

        return $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.annaba.__SLUG__.__VIEW__');
    }
}

STUB),
        };
    }

    /* ------------------------------------------------------------------ */
    /*  STUBS VUES                                                         */
    /* ------------------------------------------------------------------ */

    protected function viewStub(string $name): string
    {
        return match ($name) {
            'dashboard' => <<<'STUB'
<div class="flex w-full">

    @livewire('annaba.__SLUG__.sidebar')

    <div class="bg-[#DDE1E6] w-full min-h-[100vh]">

        @livewire('annaba.__SLUG__.navbar')

        <div class="grid max-[600px]:grid-cols-1 max-[1000px]:grid-cols-2 grid-cols-3 p-[10px] pb-[0px] gap-[10px]">
            @livewire('adminannaba.widget1')
            @livewire('adminannaba.widget1')
            @livewire('adminannaba.widget1')
        </div>

        <div class="grid max-[600px]:grid-cols-1 max-[1000px]:grid-cols-2 grid-cols-3 p-[10px] pb-[0px] gap-[10px]">
            @livewire('adminannaba.chart1')
            @livewire('adminannaba.chart1')
            @livewire('adminannaba.chart1')
        </div>

        <div class="grid max-[600px]:grid-cols-1 max-[1000px]:grid-cols-2 grid-cols-3 p-[10px] pb-[10px] gap-[10px]">
            @livewire('adminannaba.widget1')
            @livewire('adminannaba.widget1')
            @livewire('adminannaba.widget1')
        </div>

    </div>

</div>

STUB,
            'login' => <<<'STUB'
<div class="min-h-screen bg-black flex items-center justify-center px-4">
    <form wire:submit="login" class="w-full max-w-sm bg-zinc-900 p-8 rounded-xl space-y-4">

        <h1 class="text-white text-xl font-semibold mb-4 text-center">Connexion — __TITLE__</h1>

        <div>
            <input wire:model="email" type="email" placeholder="Email"
                class="w-full px-4 py-3 rounded-[2px] bg-zinc-800 text-white
                border border-gray-200 focus:outline-none focus:border-blue-600">
            @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <input wire:model="password" type="password" placeholder="Mot de passe"
                class="w-full px-4 py-3 rounded-[2px] bg-zinc-800 text-white
                 border border-gray-200 focus:outline-none focus:border-blue-600">
            @error('password') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-center gap-2 text-gray-400 text-sm">
            <input wire:model="remember" type="checkbox" class="rounded bg-zinc-800 border-white">
            Se souvenir de moi
        </label>

        <button type="submit" class="w-full bg-blue-600 text-white font-medium
                 py-3 rounded-[2px] hover:bg-blue-400">
            Se connecter
        </button>
    </form>
</div>

STUB,
            'navbar' => <<<'STUB'
<div class="min-[800px]:hidden w-full" x-data="{open1: false, open2: false}">

    <div class="bg-black h-[60px] w-full">
        <div class="bg-black h-[60px] w-full flex text-white fixed">
            <div class="w-[80px] h-[60px] pt-[9px] pl-[5px]">
                <span class="material-icons text-[40px] cursor-pointer" @click="open2=true">menu</span>
            </div>
            <div class="w-full h-[60px] text-center text-[22px] pt-[10px]">
                __TITLE__
            </div>
            <div class="w-[60px] h-[60px] pt-[9px] pl-[5px]">
                <span class="material-icons text-[40px] cursor-pointer" @click="open1=true">person</span>
            </div>
        </div>
    </div>

    <div class="fixed inset-0 z-50 flex items-start justify-center bg-black overflow-y-auto
                p-[20px] pt-[20px] pb-[20px] text-white" x-show="open1">
        <div class="w-full">
            <div>
                <span class="material-icons text-[40px] cursor-pointer" @click="open1=false">arrow_back</span>
            </div>
            <div class="h-[74px] bg-[#444] text-center text-[22px] pt-[16px] cursor-pointer">
                <span wire:click="logout" wire:confirm="Voulez-vous vraiment vous déconnecter ?">
                    Se Deconnecter
                </span>
            </div>
        </div>
    </div>

    <div class="fixed inset-0 z-50 flex items-start justify-center bg-black overflow-y-auto
                pt-[20px] pb-[20px] text-white" x-show="open2">
        <div class="w-full">
            <div>
                <span class="material-icons text-[40px] cursor-pointer" @click="open2=false">arrow_back</span>
            </div>
        </div>
    </div>

</div>

STUB,
            'sidebar' => <<<'STUB'
<div class="min-h-[100vh] bg-black min-w-[220px] text-white max-[799px]:hidden">
    <div class="h-[68px] w-full p-[10px] py-[10px]">
      <a href="/admin" wire:navigate.hover>
        <div class="bg-[#000000] h-[48px] min-w-[180px] m-auto text-center
                    text-white text-[24px] font-bold border-white border-[1px]
                    rounded-[4px] pt-[3px] cursor-pointer">
            <span>__TITLE__</span>
        </div>
      </a>   
    </div>

     <div class="flex gap-[10px] pl-[8px] text-[22px] pt-[16px] cursor-pointer">
      <div class="pt-[4px]">
        <span class="material-icons cursor-pointer"
        wire:click="logout" wire:confirm="Voulez-vous vraiment vous déconnecter ?">toggle_off</span>
      </div>
      <div>
        <span wire:click="logout" wire:confirm="Voulez-vous vraiment vous déconnecter ?">
           Deconnexion
        </span>
      </div>
    </div>

    @include('adminannaba::composants.btnLink',
                ['chemin' => '/admin', 'label' => 'Posts', 'icon' => 'edit'])


</div>

STUB,
        };
    }
}