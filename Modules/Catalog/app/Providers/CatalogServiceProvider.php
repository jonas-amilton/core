<?php

declare(strict_types=1);

namespace Modules\Catalog\Providers;

use Modules\Catalog\Contracts\CatalogRepository;
use Modules\Catalog\Repositories\EloquentCatalogRepository;
use Nwidart\Modules\Support\ModuleServiceProvider;

class CatalogServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Catalog';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'catalog';

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

        $this->app->bind(CatalogRepository::class, EloquentCatalogRepository::class);
    }
}
