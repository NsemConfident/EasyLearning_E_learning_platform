<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use App\Models\PastQuestion;
use App\Policies\PastQuestionPolicy;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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
        $this->ensureUploadDirectoriesExist();
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
     * Ensure storage directories used for file uploads exist and are writable.
     * Fixes "failed to upload" when Livewire temp or public past-questions dir is missing.
     */
    protected function ensureUploadDirectoriesExist(): void
    {
        $dirs = [
            storage_path('app/public'),
            storage_path('app/public/past-questions'),
            storage_path('app/public/past-question-answers'),
            storage_path('app/public/lesson-videos'),
            storage_path('app/public/livewire-tmp'), // Livewire temp uploads when disk is 'public'
            storage_path('app/private'),
            storage_path('app/private/livewire-tmp'),
        ];
        foreach ($dirs as $dir) {
            if (! File::isDirectory($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
        }
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
