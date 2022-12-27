<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PokerService;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        $this->app->bind(PokerService::class, function($app) {
            return new PokerService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
    }
}
