<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Manutencao;
use Illuminate\Support\Facades\Log;

class ManutencaoObserver
{

    public function creating(Manutencao $manutencao): void
    {
        $generator = new CodeGeneratorService();

        if (empty($manutencao->chamado)) {
            $cliente = $manutencao->cliente ?? Cliente::find($manutencao->cliente_id);
            
            if ($cliente) {
                $manutencao->chamado = $generator->gerarCodigoManutencao($cliente, $manutencao->tipo);
            }
        }
    }

    /**
     * Handle the Manutencao "created" event.
     */
    public function created(Manutencao $manutencao): void
    {
        try {
            $descricao = "Manutenção {$manutencao->tipo}";
            
            if ($manutencao->tipo === 'Corretiva' && $manutencao->chamado) {
                $descricao .= " (Chamado: {$manutencao->chamado})";
            }
            $nomeCliente = $manutencao->cliente ? $manutencao->cliente->nome : 'N/A';
            
            $clienteInfo = $manutencao->cliente_id ? " para o cliente {$nomeCliente}" : "";
            $descricao .= " foi agendada{$clienteInfo}.";

            Activity::create([
                'description' => $descricao
            ]);

        } catch (\Exception $e) {
            Log::error("Erro ao criar Activity no ManutencaoObserver (created): " . $e->getMessage());
        }
    }

    /**
     * Handle the Manutencao "updated" event.
     */
    public function updated(Manutencao $manutencao): void
    {
       try {
            if (!$manutencao->isDirty()) {
                return;
            }

            $descricao = "Manutenção {$manutencao->tipo}";
            
            if ($manutencao->tipo === 'Corretiva' && $manutencao->chamado) {
                 $descricao .= " (Chamado: {$manutencao->chamado})";
            }
            
            $nomeCliente = $manutencao->cliente ? $manutencao->cliente->nome : 'N/A';
            
            $clienteInfo = $manutencao->cliente_id ? " para o cliente {$nomeCliente}" : "";
            $descricao .= " foi atualizada{$clienteInfo}.";

            if ($manutencao->isDirty('status')) {
                $descricao .= " Status alterado para: {$manutencao->status}.";
            }

            Activity::create([
                'description' => $descricao
            ]);

        } catch (\Exception $e) {
            Log::error("Erro ao criar Activity no ManutencaoObserver (updated): " . $e->getMessage());
        }
    }

    /**
     * Handle the Manutencao "deleted" event.
     */
    public function deleted(Manutencao $manutencao): void
    {
        try {
            $descricao = "Manutenção {$manutencao->tipo}";
            
            if ($manutencao->tipo === 'Corretiva' && $manutencao->chamado) {
                 $descricao .= " (Chamado: {$manutencao->chamado})";
            }
            
            // Obtém o nome do cliente
            $nomeCliente = $manutencao->cliente ? $manutencao->cliente->nome : 'N/A';
            
            $clienteInfo = $manutencao->cliente_id ? " do cliente {$nomeCliente}" : "";
            $descricao .= "{$clienteInfo} foi deletada.";

            Activity::create([
                'description' => $descricao
            ]);
            
        } catch (\Exception $e) {
            Log::error("Erro ao criar Activity no ManutencaoObserver (deleted): " . $e->getMessage());
        }
    }
}