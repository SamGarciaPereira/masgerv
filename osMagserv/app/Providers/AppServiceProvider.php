<?php

namespace App\Providers;

use App\Models\Cliente;
use App\Models\Orcamento;
use App\Observers\ClienteObserver;
use App\Observers\OrcamentoObserver;
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
        Cliente::observe(ClienteObserver::class);

        Orcamento::observe(OrcamentoObserver::class);
    }
}
