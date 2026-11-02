<?php

declare(strict_types=1);

namespace Modules\Quote\Providers;

use Inertia\Inertia;
use Modules\Quote\Contracts\QuoteRepository;
use Modules\Quote\Repositories\EloquentQuoteRepository;
use Modules\Quote\Services\Cart;
use Nwidart\Modules\Support\ModuleServiceProvider;

class QuoteServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Quote';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'quote';

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

        $this->app->bind(QuoteRepository::class, EloquentQuoteRepository::class);
    }

    public function boot(): void
    {
        parent::boot();

        // Header counter shared with every public page.
        Inertia::share('cart_count', fn (): int => request()->hasSession()
            ? $this->app->make(Cart::class)->count()
            : 0);
    }
}
