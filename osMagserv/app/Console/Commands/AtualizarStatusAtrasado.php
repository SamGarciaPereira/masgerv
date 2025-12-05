<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ContasPagar;
use App\Models\ContasReceber;
use Carbon\Carbon;

class AtualizarStatusAtrasado extends Command
{
    protected $signature = 'financeiro:atualizar-atrasados';

    protected $description = 'Atualiza para Atrasado contas vencidas e não pagas';

    public function handle()
    {
        $hoje = Carbon::now()->startOfDay();

        $pagarAfetadas = ContasPagar::where('status', 'Pendente')
            ->whereDate('data_vencimento', '<', $hoje)
            ->update(['status' => 'Atrasado']);

        $receberAfetadas = ContasReceber::where('status', 'Pendente')
            ->whereDate('data_vencimento', '<', $hoje)
            ->update(['status' => 'Atrasado']);

        $this->info("Sucesso! Contas a Pagar atualizadas: $pagarAfetadas. Contas a Receber atualizadas: $receberAfetadas.");
    }
}