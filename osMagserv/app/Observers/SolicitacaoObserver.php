<?php

namespace App\Observers;

use App\Models\Activity; 
use App\Models\Solicitacao;

class SolicitacaoObserver
{
    /**
     * Handle the Solicitacao "created" event.
     */
    public function created(Solicitacao $solicitacao): void
    {
        $horario = $solicitacao->data_solicitacao 
            ? $solicitacao->data_solicitacao->format('H:i') 
            : now()->format('H:i');

        if($solicitacao->tipo === 'orcamento') {
            $tipoTexto = 'orçamento';
        } elseif($solicitacao->tipo === 'manutencao_corretiva') {
            $tipoTexto = 'manutenção corretiva';
        } else {
            $tipoTexto = $solicitacao->tipo;
        }

        Activity::create([
            'description' => "Nova solicitação (#{$solicitacao->id}) de {$tipoTexto}, às {$horario}",
        ]);
    }

    /**
     * Handle the Solicitacao "updated" event.
     */
    public function updated(Solicitacao $solicitacao): void
    {
        if ($solicitacao->isDirty('status')) {
            $novoStatus = $solicitacao->status;
            if (in_array($novoStatus, ['Aceita', 'Recusada'])) {
                Activity::create([
                    'description' => "Solicitação #{$solicitacao->id} foi {$novoStatus}",
                ]);
            }
        }
    }

    /**
     * Handle the Solicitacao "deleted" event.
     */
    public function deleted(Solicitacao $solicitacao): void
    {
        //
    }

    /**
     * Handle the Solicitacao "restored" event.
     */
    public function restored(Solicitacao $solicitacao): void
    {
        //
    }

    /**
     * Handle the Solicitacao "force deleted" event.
     */
    public function forceDeleted(Solicitacao $solicitacao): void
    {
        //
    }
}