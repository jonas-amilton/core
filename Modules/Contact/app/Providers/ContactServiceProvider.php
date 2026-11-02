<?php

declare(strict_types=1);

namespace Modules\Contact\Providers;

use Modules\Contact\Contracts\ContactRepository;
use Modules\Contact\Repositories\EloquentContactRepository;
use Nwidart\Modules\Support\ModuleServiceProvider;

class ContactServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Contact';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'contact';

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

        $this->app->bind(ContactRepository::class, EloquentContactRepository::class);
    }
}
