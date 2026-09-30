<?php

namespace Annaba\Admin\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'annaba:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Annaba Package';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Filesystem $files): int
    {
        $this->createRoutesFile($files);
        $this->requireInWebRoutes($files);

        $this->info('Annaba installé avec succès.');

        return self::SUCCESS;
    }

    /**
     * Crée routes/annaba.php.
     */
    protected function createRoutesFile(Filesystem $files): void
    {
        $path = base_path('routes/annaba.php');

        if ($files->exists($path) && ! $this->option('force')) {
            $this->warn('routes/annaba.php existe déjà (utilise --force pour l\'écraser).');

            return;
        }

        $files->put($path, $this->routesStub());

        $this->info('Fichier routes/annaba.php créé.');
    }

    /**
     * Ajoute le require de annaba.php dans routes/web.php (sans doublon).
     */
    protected function requireInWebRoutes(Filesystem $files): void
    {
        $webPath = base_path('routes/web.php');

        if (! $files->exists($webPath)) {
            $this->error('routes/web.php introuvable.');

            return;
        }

        $content = $files->get($webPath);

        if (str_contains($content, 'annaba.php')) {
            $this->line('routes/web.php contient déjà le require de annaba.php.');

            return;
        }

        $files->append(
            $webPath,
            PHP_EOL . "require __DIR__ . '/annaba.php';" . PHP_EOL
        );

        $this->info('require ajouté dans routes/web.php.');
    }

    /**
     * Contenu du fichier routes/annaba.php.
     */
    protected function routesStub(): string
    {
        return <<<'PHP'
<?php

use Illuminate\Support\Facades\Route;

PHP;
    }
}