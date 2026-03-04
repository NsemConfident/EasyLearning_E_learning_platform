<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use App\Models\PastQuestion;
use App\Policies\PastQuestionPolicy;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $this->configureDefaults();

        // Ensure Filament (and other) asset URLs use the current request URL in local dev,
        // so styles load correctly when using php artisan serve or a different host/port.
        if (app()->environment('local') && app()->runningInConsole() === false && request()->getSchemeAndHttpHost()) {
            URL::forceRootUrl(request()->getSchemeAndHttpHost());
        }

        Gate::policy(PastQuestion::class, PastQuestionPolicy::class);

        Gate::define('managePastQuestions', function ($user) {
            return $user->isAdmin();
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
