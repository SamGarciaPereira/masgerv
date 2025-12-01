<?php

namespace App\Observers;

use App\Models\Contrato;
use App\Services\CodeGeneratorService;
use App\Models\Cliente;

class ContratoObserver
{
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