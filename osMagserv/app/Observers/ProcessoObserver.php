<?php

namespace App\Observers;

use App\Models\Processo;

class ProcessoObserver
{
    /**
     * Handle the Processo "created" event.
     */
    public function created(Processo $processo): void
    {
        //
    }

    /**
     * Handle the Processo "updated" event.
     */
    public function updated(Processo $processo)
    {

        $processo->load('orcamento');

        if ($processo->isDirty('status') && $processo->status === 'Faturado') {

            if ($processo->orcamento) {
                ContasReceberObserver::create([
                    'descricao' => 'Faturamento do orçamento: ' . $processo->orcamento->numero_proposta,
                    'valor' => $processo->orcamento->valor,
                    'data_vencimento' => now()->addDays(30), // Vencimento para 30 dias (exemplo)
                    'status' => 'Pendente',
                    'cliente_id' => $processo->orcamento->cliente_id,
                ]);
            }
        }
    }

    /**
     * Handle the Processo "deleted" event.
     */
    public function deleted(Processo $processo): void
    {
        //
    }

    /**
     * Handle the Processo "restored" event.
     */
    public function restored(Processo $processo): void
    {
        //
    }

    /**
     * Handle the Processo "force deleted" event.
     */
    public function forceDeleted(Processo $processo): void
    {
        //
    }
}
