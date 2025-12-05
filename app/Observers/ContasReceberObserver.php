<?php

namespace App\Observers;

use App\Models\ContasReceber;
use App\Models\Activity;
use App\Models\Processo;
use App\Models\Orcamento;

class ContasReceberObserver
{
    /**
     * Handle the ContasReceber "created" event.
     */
    public function created(ContasReceber $contasReceber): void
    {
         if ($contasReceber->processo_id) {
            $contasReceber->load('processo.orcamento');

            if ($contasReceber->processo && $contasReceber->processo->orcamento) {
                $orcamento = $contasReceber->processo->orcamento;
                
                $identificador = $contasReceber->nf 
                    ? "da NF {$contasReceber->nf}" 
                    : "do orçamento {$orcamento->numero_proposta}";

                Activity::create([
                    'description' => "Processo {$identificador} foi faturado e uma nova conta a receber foi gerada."
                ]);
            }
        } else {
            Activity::create([
                'description' => "Uma nova conta a receber foi cadastrada: '{$contasReceber->descricao}'."
            ]);
        }   
    }

    /**
     * Handle the ContasReceber "updated" event.
     */
    public function updated(ContasReceber $contasReceber): void
    {
        $contasReceber->load('processo.orcamento');

        if ($contasReceber->processo && $contasReceber->processo->orcamento) {
            $orcamento = $contasReceber->processo->orcamento;
            Activity::create([
                'description' => "A conta a receber do orçamento '{$orcamento->numero_proposta}' foi atualizada."
            ]);
        } else {
            Activity::create([
                'description' => "A conta a receber '{$contasReceber->descricao}' foi atualizada."
            ]);
        }
    }

    /**
     * Handle the ContasReceber "deleted" event.
     */
    public function deleted(ContasReceber $contasReceber): void
    {
        $contasReceber->load('processo.orcamento');

        if ($contasReceber->processo && $contasReceber->processo->orcamento) {
            $orcamento = $contasReceber->processo->orcamento;
            Activity::create([
                'description' => "A conta a receber do orçamento '{$orcamento->numero_proposta}' foi deletada."
            ]);
        } else {
            Activity::create([
                'description' => "A conta a receber '{$contasReceber->descricao}' foi deletada."
            ]);
        }
    }

    /**
     * Handle the ContasReceber "restored" event.
     */
    public function restored(ContasReceber $contasReceber): void
    {
        //
    }

    /**
     * Handle the ContasReceber "force deleted" event.
     */
    public function forceDeleted(ContasReceber $contasReceber): void
    {
        //
    }
}
