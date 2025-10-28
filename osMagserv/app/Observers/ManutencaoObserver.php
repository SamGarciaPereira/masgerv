<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Manutencao;
use Illuminate\Support\Facades\Log; // Para registar erros

class ManutencaoObserver
{
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
            $clienteInfo = $manutencao->cliente_id ? " para o cliente ID {$manutencao->cliente_id}" : "";
            $descricao .= " foi agendada{$clienteInfo}.";

            Activity::create([
                'cliente_id' => $manutencao->cliente_id, 
                'descricao' => $descricao
            ]);
        } catch (\Exception $e) {
            Log::error("Erro ao criar Activity no ManutencaoObserver (created): " . $e->getMessage());
        }
    }

    /**
     * Handle the Manutencao "updating" event.
     */
    public function updating(Manutencao $manutencao): void
    {
        
    }


    /**
     * Handle the Manutencao "updated" event.
     */
    public function updated(Manutencao $manutencao): void
    {
       try {
            $changes = $manutencao->getChanges();
            unset($changes['updated_at']);

            if (empty($changes)) {
                return;
            }

            $descricao = "Manutenção {$manutencao->tipo}";
             if ($manutencao->tipo === 'Corretiva' && $manutencao->chamado) {
                 $descricao .= " (Chamado: {$manutencao->chamado})";
             }
             $clienteInfo = $manutencao->cliente_id ? " para o cliente ID {$manutencao->cliente_id}" : "";
             $descricao .= " foi atualizada{$clienteInfo}.";

            Activity::create([
                'cliente_id' => $manutencao->cliente_id,
                'descricao' => $descricao
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
             $clienteInfo = $manutencao->cliente_id ? " do cliente ID {$manutencao->cliente_id}" : "";
             $descricao .= "{$clienteInfo} foi deletada.";

            Activity::create([
                'cliente_id' => $manutencao->cliente_id,
                'descricao' => $descricao
            ]);
        } catch (\Exception $e) {
            Log::error("Erro ao criar Activity no ManutencaoObserver (deleted): " . $e->getMessage());
        }
    }

    /**
     * Handle the Manutencao "restored" event.
     */
    public function restored(Manutencao $manutencao): void
    {
        //
    }

    /**
     * Handle the Manutencao "force deleted" event.
     */
    public function forceDeleted(Manutencao $manutencao): void
    {
        //
    }
}