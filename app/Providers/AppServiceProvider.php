<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Form;
use App\Models\Cdc;
use App\Policies\FormPolicy;
use App\Policies\CdcPolicy;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;

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
        Gate::policy(Form::class, FormPolicy::class);
        Gate::policy(Cdc::class, CdcPolicy::class);
        if ($this->app->environment('production') || env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }
}
