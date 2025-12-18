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
        if ($processo->wasChanged()) {
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
