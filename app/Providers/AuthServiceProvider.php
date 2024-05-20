<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Video;
use App\Policies\VideoPolicy;
use Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Video::class => VideoPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        // Passport::routes();
        Passport::tokensExpireIn(now()->addMinutes(config("auth.token_expiration")));
        Passport::refreshTokensExpireIn(now()->addMinutes(config("auth.refresh_token_expiration")));

        Gate::before(function () {
            // return auth()->check() && auth()->user()->isAdmin();
        });
    }
}
