<?php

namespace App\Providers;

use App\Models\Cliente;
use App\Observers\ClienteObserver;
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
        // Adicione esta linha para registar o observer
        Cliente::observe(ClienteObserver::class);
    }
}
