<?php

namespace App\Services;

use App\Models\Manutencao;
use App\Models\Contrato;
use App\Models\Orcamento;
use App\Models\Cliente;
use Carbon\Carbon;

class CodeGeneratorService
{
    protected $base = '01'; 

    /**
     * Gera código para Manutenção (Preventiva ou Corretiva)
     * Formato: EST-BASE-YYYYMM-T-CLI(3)-GER(3)-OCC(2)
     */
    public function gerarCodigoManutencao(Cliente $cliente, string $tipo)
    {
        $now = Carbon::now();
        $anoMes = $now->format('mY'); // Ex: 112025
        $estado = $cliente->uf ?? 'PR'; // Padrão PR se não tiver UF
        
        $tipoLetra = ($tipo === 'Preventiva') ? 'P' : 'C';

        $idClienteStr = str_pad($cliente->id, 3, '0', STR_PAD_LEFT);

        $countGeral = Manutencao::where('tipo', $tipo)->count() + 1;
        $geralStr = str_pad($countGeral, 3, '0', STR_PAD_LEFT);

        $countClienteMes = Manutencao::where('cliente_id', $cliente->id)
            ->where('tipo', $tipo)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count() + 1;
        $ocorrenciaStr = str_pad($countClienteMes, 2, '0', STR_PAD_LEFT);

        // Resultado Ex: PR-01-202511-P-001-050-01
        return "{$estado}-{$this->base}-{$anoMes}-{$tipoLetra}-{$idClienteStr}-{$geralStr}-{$ocorrenciaStr}";
    }

    /**
     * Gera código para Orçamento
     * Formato: EST-BASE-YYYYMM-O-PPP(3)
     */
    public function gerarCodigoOrcamento(Cliente $cliente = null)
    {
        $now = Carbon::now();
        $anoMes = $now->format('mY');
        $estado = $cliente ? ($cliente->uf ?? 'PR') : 'PR';

        // Número Sequencial Global de Orçamentos
        $countProposta = Orcamento::count() + 1;
        $propostaStr = str_pad($countProposta, 3, '0', STR_PAD_LEFT);

        // Resultado Ex: PR-01-202511-O-005
        return "{$estado}-{$this->base}-{$anoMes}-O-{$propostaStr}";
    }

    /**
     * Gera código para Contrato
     * Formato: EST-CLI(3)-CTGGG(3)-YYYY
     */
    public function gerarCodigoContrato(Cliente $cliente)
    {
        $now = Carbon::now();
        $anoVigente = $now->format('Y');
        $estado = $cliente->uf ?? 'PR';

        $idClienteStr = str_pad($cliente->id, 3, '0', STR_PAD_LEFT);

        $countContrato = Contrato::count() + 1;
        $idContratoStr = str_pad($countContrato, 3, '0', STR_PAD_LEFT);

        return "{$estado}-{$idClienteStr}-CT{$idContratoStr}-{$anoVigente}";
    }
}