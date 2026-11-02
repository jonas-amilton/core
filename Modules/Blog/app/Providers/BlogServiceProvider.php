<?php

declare(strict_types=1);

namespace Modules\Blog\Providers;

use Modules\Blog\Contracts\BlogRepository;
use Modules\Blog\Repositories\EloquentBlogRepository;
use Nwidart\Modules\Support\ModuleServiceProvider;

class BlogServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Blog';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'blog';

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

        $this->app->bind(BlogRepository::class, EloquentBlogRepository::class);
    }
}
