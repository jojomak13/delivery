<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Twilio\Rest\Client;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

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

        Collection::macro('paginate', function ($perPage = 15, $page = null, $options = []) {
            $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
            return (
                new Paginator(
                    $this->forPage($page, $perPage)->values(), 
                    $perPage, 
                    $page, 
                    $options
                )
            )->withPath('');
        });
    }
}
