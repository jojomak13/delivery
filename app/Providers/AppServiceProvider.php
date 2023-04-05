<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Twilio\Rest\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(Client::class, function(){
            return new Client(config('services.twilio.account_sid'), config('services.twilio.auth_token'));
        });
    }
}
