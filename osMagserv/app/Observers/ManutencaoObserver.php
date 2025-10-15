<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Manutencao;


class ManutencaoObserver
{
    /**
     * Handle the Manutencao "created" event.
     */
    public function created(Manutencao $manutencao): void
    {
        Activity::create([
            'description' => "Manutenção '{$manutencao->chamado}' foi agendada."
        ]);
    }

    /**
     * Handle the Manutencao "updated" event.
     */
    public function updated(Manutencao $manutencao): void
    {
        Activity::create([
            'description' => "Manutenção '{$manutencao->chamado}' foi atualizada."
        ]);
    }

    /**
     * Handle the Manutencao "deleted" event.
     */
    public function deleted(Manutencao $manutencao): void
    {
        Activity::create([
            'description' => "Manutenção '{$manutencao->chamado}' foi deletada."
        ]);
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
