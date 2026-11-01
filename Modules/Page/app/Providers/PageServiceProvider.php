<?php

declare(strict_types=1);

namespace Modules\Page\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Modules\Page\Contracts\PageRepository;
use Modules\Page\Repositories\EloquentPageRepository;
use Nwidart\Modules\Support\ModuleServiceProvider;

class PageServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Page';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'page';

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

        $this->app->bind(PageRepository::class, EloquentPageRepository::class);
    }

    /**
     * Define module schedules.
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }
}
