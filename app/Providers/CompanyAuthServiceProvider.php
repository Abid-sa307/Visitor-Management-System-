<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Log;

class CompanyAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Extend the Auth system with our custom guard
        Auth::extend('company', function ($app, $name, array $config) {
            $provider = Auth::createUserProvider($config['provider']);
            
            $guard = new \Illuminate\Auth\SessionGuard(
                $name,
                $provider,
                $app['session.store'],
                $app['request']
            );

            // When using the remember me functionality of the authentication services we
            // will need to be set the encryption instance of the guard, which allows
            // secure, encrypted cookie values to get generated for those cookies.
            if (method_exists($guard, 'setCookieJar')) {
                $guard->setCookieJar($app['cookie']);
            }

            if (method_exists($guard, 'setDispatcher')) {
                $guard->setDispatcher($app['events']);
            }

            if (method_exists($guard, 'setRequest')) {
                $guard->setRequest($app->refresh('request', $guard, 'setRequest'));
            }

            return $guard;
        });

        // Register the user provider
        Auth::provider('company_users', function ($app, array $config) {
            return new \Illuminate\Auth\EloquentUserProvider(
                $app['hash'],
                $config['model']
            );
        });
    }
}
