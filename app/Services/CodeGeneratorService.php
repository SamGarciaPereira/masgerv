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
        $anoMes = $now->format('my'); // Ex: 1125
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

        // Resultado Ex: PR-01-1125-P-001-050-01
        return "{$estado}-{$this->base}-{$anoMes}-{$tipoLetra}-{$idClienteStr}-{$geralStr}-{$ocorrenciaStr}";
    }

    /**
     * Formata a string do código (Centraliza a regra visual)
     */
    public function formatarCodigoOrcamento(Cliente $cliente, Carbon $data, int $sequencial)
    {
        $anoMes = $data->format('my');
        $estado = $cliente->uf ?? 'PR';
        $sequenciaStr = str_pad($sequencial, 3, '0', STR_PAD_LEFT);

        return "{$estado}-{$this->base}-{$anoMes}-O-{$sequenciaStr}";
    }

    /**
     * Gera o código (Automático ou Manual)
     */
    public function gerarCodigoOrcamento(Cliente $cliente = null, ?int $numeroManual = null, ?Carbon $dataReferencia = null)
    {
        // Se dataReferencia (solicitação) for passada, usa ela. Senão, usa Now.
        $dataBase = $dataReferencia ?? Carbon::now();

        $cliente = $cliente ?? new Cliente(['uf' => 'PR']);

        if ($numeroManual !== null) {
            return $this->formatarCodigoOrcamento($cliente, $dataBase, $numeroManual);
        }

        // Gera o prefixo com base na data correta (Ex: PR-01-1025-O-)
        $prefixoBusca = $this->formatarCodigoOrcamento($cliente, $dataBase, 0);
        $prefixoBusca = substr($prefixoBusca, 0, strrpos($prefixoBusca, '-') + 1);

        $ultimoOrcamento = Orcamento::where('numero_proposta', 'like', "{$prefixoBusca}%")
            ->orderBy('id', 'desc')
            ->first();

        if ($ultimoOrcamento) {
            $partes = explode('-', $ultimoOrcamento->numero_proposta);
            $ultimoNumero = (int) end($partes);
            $proximoSequencial = $ultimoNumero + 1;
        } else {
            $proximoSequencial = 1;
        }

        $codigoFinal = $this->formatarCodigoOrcamento($cliente, $dataBase, $proximoSequencial);

        // Verifica colisão
        while (Orcamento::where('numero_proposta', $codigoFinal)->exists()) {
            $proximoSequencial++;
            $codigoFinal = $this->formatarCodigoOrcamento($cliente, $dataBase, $proximoSequencial);
        }

        return $codigoFinal;
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