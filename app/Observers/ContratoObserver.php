<?php

namespace App\Observers;

use App\Models\Contrato;
use App\Services\CodeGeneratorService;
use App\Models\Cliente;

class ContratoObserver
{

    public function saving(Contrato $contrato): void
    {
        $hoje = \Carbon\Carbon::now()->startOfDay();

        if ($contrato->data_fim && $contrato->data_fim < $hoje) {
            $contrato->ativo = false;
        }

        if ($contrato->data_inicio && $contrato->data_inicio > $hoje) {
            $contrato->ativo = false;
        }
    }

    public function creating(Contrato $contrato): void
    {
        $generator = new CodeGeneratorService();

        if (empty($contrato->numero_contrato)) {
            $cliente = $contrato->cliente ?? Cliente::find($contrato->cliente_id);
            
            if ($cliente) {
                $contrato->numero_contrato = $generator->gerarCodigoContrato($cliente);
            }
        }
    }
}