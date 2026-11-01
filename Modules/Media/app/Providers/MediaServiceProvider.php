<?php

declare(strict_types=1);

namespace Modules\Media\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Modules\Media\Contracts\MediaRepository;
use Modules\Media\Repositories\EloquentMediaRepository;
use Nwidart\Modules\Support\ModuleServiceProvider;

class MediaServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Media';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'media';

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

    public function register(): void
    {
        parent::register();

        $this->app->bind(MediaRepository::class, EloquentMediaRepository::class);
    }

    /**
     * Define module schedules.
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }
}
