<?php

namespace App\Providers;

use App\Models\Cliente;
use App\Models\Orcamento;
use App\Models\Processo;
use App\Models\ContasPagar;
use App\Models\ContasReceber;
use App\Models\Manutencao;
use App\Observers\ClienteObserver;
use App\Observers\OrcamentoObserver;
use App\Observers\ProcessoObserver;
use App\Observers\ContasPagarObserver;
use App\Observers\ContasReceberObserver;
use App\Observers\ManutencaoObserver;
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
        Processo::observe(ProcessoObserver::class);
        ContasPagar::observe(ContasPagarObserver::class);
        ContasReceber::observe(ContasReceberObserver::class);
        Orcamento::observe(OrcamentoObserver::class);
        Manutencao::observe(ManutencaoObserver::class);
    }
}
