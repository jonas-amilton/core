<?php

declare(strict_types=1);

namespace Modules\Settings\Providers;

use Inertia\Inertia;
use Modules\Settings\Contracts\SettingsRepository;
use Modules\Settings\Repositories\EloquentSettingsRepository;
use Modules\Settings\Services\SettingsService;
use Nwidart\Modules\Support\ModuleServiceProvider;

class SettingsServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Settings';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'settings';

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

        $this->app->bind(SettingsRepository::class, EloquentSettingsRepository::class);
    }

    public function boot(): void
    {
        parent::boot();

        // Site-wide contact data, shared with every Inertia response.
        Inertia::share('site', fn (): array => $this->app->make(SettingsService::class)->all());
    }
}
