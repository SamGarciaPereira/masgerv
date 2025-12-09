<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contrato;
use Carbon\Carbon;

class VerificarValidadeContratos extends Command
{
    protected $signature = 'contratos:verificar-validade';
    protected $description = 'Ativa contratos iniciados e inativa contratos vencidos';

    public function handle()
    {
        $hoje = Carbon::now()->startOfDay();

        $vencidos = Contrato::where('ativo', true)
            ->whereDate('data_fim', '<', $hoje)
            ->update(['ativo' => false]);

        $iniciados = Contrato::where('ativo', false)
            ->whereDate('data_inicio', '<=', $hoje)
            ->whereDate('data_fim', '>=', $hoje)
            ->update(['ativo' => true]);

        $this->info("Sucesso! Vencidos inativados: $vencidos. Iniciados ativados: $iniciados.");
    }
}