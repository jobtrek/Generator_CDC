<?php

namespace App\Providers;

use App\Models\Cdc;
use App\Models\Form;
use App\Models\User;
use App\Policies\CdcPolicy;
use App\Policies\FormPolicy;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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

        Gate::before(function (User $user) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Event::listen(PasswordReset::class, function ($event) {
            if (! $event->user->hasVerifiedEmail()) {
                $event->user->markEmailAsVerified();
            }
        });
    }
}
