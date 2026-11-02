<?php

declare(strict_types=1);

namespace Modules\Content\Providers;

use Modules\Content\Contracts\ContentRepository;
use Modules\Content\Repositories\EloquentContentRepository;
use Nwidart\Modules\Support\ModuleServiceProvider;

class ContentServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Content';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'content';

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

        $this->app->bind(ContentRepository::class, EloquentContentRepository::class);
    }
}
