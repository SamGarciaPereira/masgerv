<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ContasPagar;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RenovarContasFixas extends Command
{
    protected $signature = 'financeiro:renovar-fixas';
    protected $description = 'Renova as contas a pagar fixas para o novo ano';

    public function handle()
    {
        $anoNovo = now()->year;
        $anoPassado = $anoNovo - 1;

        $this->info("Buscando contas fixas de Dezembro de $anoPassado para renovar em $anoNovo...");

        $contasModelo = ContasPagar::where('is_fixa', true)
            ->whereYear('data_vencimento', $anoPassado)
            ->whereMonth('data_vencimento', 12)
            ->get();

        $count = 0;

        foreach ($contasModelo as $conta) {
            $diaVencimento = Carbon::parse($conta->data_vencimento)->day;

            for ($mes = 1; $mes <= 12; $mes++) {
                
                $existe = ContasPagar::where('descricao', $conta->descricao)
                    ->where('is_fixa', true)
                    ->whereYear('data_vencimento', $anoNovo)
                    ->whereMonth('data_vencimento', $mes)
                    ->exists();

                if (!$existe) {
                    try {
                        $novaData = Carbon::create($anoNovo, $mes, $diaVencimento);
                    } catch (\Exception $e) {
                        $novaData = Carbon::create($anoNovo, $mes, 1)->endOfMonth();
                    }

                    ContasPagar::create([
                        'descricao' => $conta->descricao,
                        'valor' => $conta->valor,
                        'data_vencimento' => $novaData,
                        'status' => 'Pendente',
                        'categoria' => $conta->categoria,
                        'fornecedor_id' => $conta->fornecedor_id,
                        'is_fixa' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            $count++;
        }

        $this->info("Sucesso! $count tipos de contas fixas foram renovadas para os 12 meses de $anoNovo.");
        Log::info("Renovação de contas fixas executada. $count contas matrizes processadas.");
    }
}