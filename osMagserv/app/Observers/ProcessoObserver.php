<?php

namespace App\Observers;

use App\Models\Processo;
use App\Models\Activity;
use App\Models\ContasReceber;

class ProcessoObserver
{
    /**
     * Handle the Processo "created" event.
     */
    public function created(Processo $processo): void
    {
        Activity::create([
            'description' => "Processo para o orçamento '{$processo->orcamento->numero_proposta}' foi criado."
        ]);
    }

    /**
     * Handle the Processo "updated" event.
     */
    public function updated(Processo $processo)
    {
        if ($processo->isDirty('status') && $processo->status === 'Faturado') {
            $processo->load('orcamento.cliente');
            if ($processo->orcamento) {
                ContasReceber::create([
                    'descricao' => 'Faturamento do orçamento: ' . $processo->orcamento->numero_proposta,
                    'valor' => $processo->orcamento->valor,
                    'data_vencimento' => null,
                    'status' => 'Pendente',
                    'cliente_id' => $processo->orcamento->cliente_id,
                    'processo_id' => $processo->id,
                    'nf' => $processo->nf,
                ]);
            }
        } else if ($processo->wasChanged()) {
            $processo->load('orcamento');
            Activity::create([
                'description' => "O processo para o orçamento '{$processo->orcamento->numero_proposta}' foi atualizado."
            ]);
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
