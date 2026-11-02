<?php

declare(strict_types=1);

namespace Modules\Store\Providers;

use Modules\Store\Contracts\StoreRepository;
use Modules\Store\Repositories\EloquentStoreRepository;
use Nwidart\Modules\Support\ModuleServiceProvider;

class StoreServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Store';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'store';

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

        $this->app->bind(StoreRepository::class, EloquentStoreRepository::class);
    }
}
