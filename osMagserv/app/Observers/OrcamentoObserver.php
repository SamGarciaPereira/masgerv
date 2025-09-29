<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Orcamento;

class OrcamentoObserver
{
    /**
     * Handle the Orcamento "created" event.
     */
    public function created(Orcamento $orcamento): void
    {
        Activity::create([
            'description' => "Orçamento '{$orcamento->numero_proposta}' foi cadastrado."
        ]);
    }

    /**
     * Handle the Orcamento "updated" event.
     */
    public function updated(Orcamento $orcamento): void
    {
        Activity::create([
            'description' => "Orçamento '{$orcamento->numero_proposta}' foi atualizado."
        ]);
    }

    /**
     * Handle the Orcamento "deleted" event.
     */
    public function deleted(Orcamento $orcamento): void
    {
        Activity::create([
            'description' => "Orçamento '{$orcamento->numero_proposta}' foi removido."
        ]);
    }

    /**
     * Handle the Orcamento "restored" event.
     */
    public function restored(Orcamento $orcamento): void
    {
        //
    }

    /**
     * Handle the Orcamento "force deleted" event.
     */
    public function forceDeleted(Orcamento $orcamento): void
    {
        //
    }
}
