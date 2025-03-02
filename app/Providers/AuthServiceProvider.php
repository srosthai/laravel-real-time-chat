<?php

namespace App\Providers;

use App\Providers\SocialAuthProviders\FacebookAuthProvider;
use App\Providers\SocialAuthProviders\GoogleAuthProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Socialite;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Socialite::extend('google', function ($app) {
            $config = $this->app['config']['services.google'];

            return new GoogleAuthProvider(
                $app['request'],
                $config['client_id'],
                $config['client_secret'],
                $config['redirect']
            );
        });

        Socialite::extend('facebook', function ($app) {
            $config = $this->app['config']['services.facebook'];

            return new FacebookAuthProvider(
                $app['request'],
                $config['client_id'],
                $config['client_secret'],
                $config['redirect']
            );
        });
    }
}
