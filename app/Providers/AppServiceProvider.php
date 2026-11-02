<?php

namespace App\Providers;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Modules\Page\Http\Controllers\Site\PageController;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('access-panel', fn (User $user): bool => $user->canUsePanel());
        Gate::define('manage-users', fn (User $user): bool => $user->isAdmin());
        Gate::define('manage-settings', fn (User $user): bool => $user->isAdmin());

        $this->configureDefaults();

        // Página pública por slug: registrada depois de todas as rotas (painel
        // e módulos) para nunca engolir /pages, /login, /admin ou /settings.
        $this->app->booted(function (): void {
            Route::get('{slug}', PageController::class)
                ->middleware('web')
                ->where('slug', '[A-Za-z0-9\-]+')
                ->name('site.page');
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
