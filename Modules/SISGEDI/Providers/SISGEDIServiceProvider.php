<?php

namespace Modules\SISGEDI\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Modules\SISGEDI\Http\Middleware\EnsureSisgediRole;

class SISGEDIServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'SISGEDI';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'sisgedi';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Registra el alias de middleware propio del modulo aqui, en vez de en
     * bootstrap/app.php, para no tocar un archivo compartido por todos los
     * subgrupos del proyecto (evita conflictos al fusionar ramas).
     */
    public function boot(): void
    {
        parent::boot();

        $this->app['router']->aliasMiddleware('rol.sisgedi', EnsureSisgediRole::class);
    }

    /**
     * Define module schedules.
     * 
     * @param $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }
}
