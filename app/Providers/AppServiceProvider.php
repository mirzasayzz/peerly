<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\Mail::extend('sendgrid', function (array $config) {
            return (new \Symfony\Component\Mailer\Bridge\Sendgrid\Transport\SendgridTransportFactory())->create(
                new \Symfony\Component\Mailer\Transport\Dsn(
                    'sendgrid+api',
                    'default',
                    config('services.sendgrid.key')
                )
            );
        });
    }
}
