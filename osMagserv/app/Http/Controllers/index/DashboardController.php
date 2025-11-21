<?php

namespace App\Http\Controllers\index;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Processo;
use App\Models\Orcamento;
use App\Models\Manutencao;
use App\Models\ContasReceber;
use App\Models\ContasPagar;
use App\Models\Solicitacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $mes = (int) $request->input('mes', now()->month);
        $ano = (int) $request->input('ano', now()->year);
        
        $getStats = function($query) {
            return $query->groupBy('status')
                         ->pluck(DB::raw('count(*) as total'), 'status')
                         ->toArray();
        };
        
        $getSumStats = function($query) {
            return $query->groupBy('status')
                         ->pluck(DB::raw('sum(valor) as total'), 'status')
                         ->toArray();
        };

        // PROCESSOS (Quantidade por Status)
        $processosStats = $getStats(
            Processo::whereMonth('created_at', $mes)->whereYear('created_at', $ano)
        );

        // ORÇAMENTOS (Quantidade por Status)
        $orcamentosStats = $getStats(
            Orcamento::whereMonth('created_at', $mes)->whereYear('created_at', $ano)
        );

        // MANUTENÇÕES PREVENTIVAS (Quantidade por Status)
        $prevStats = $getStats(
            Manutencao::where('tipo', 'Preventiva')
                      ->whereMonth('data_inicio_atendimento', $mes)
                      ->whereYear('data_inicio_atendimento', $ano)
        );
            
        // MANUTENÇÕES CORRETIVAS (Quantidade por Status)
        $corrStats = $getStats(
            Manutencao::where('tipo', 'Corretiva')
                      ->whereMonth('data_inicio_atendimento', $mes)
                      ->whereYear('data_inicio_atendimento', $ano)
        );

        // FINANCEIRO - A RECEBER (Soma de VALORES por Status)
        // Ex: ['Pago' => 5000, 'Pendente' => 2000]
        $receberStats = $getSumStats(
            ContasReceber::whereMonth('data_vencimento', $mes)->whereYear('data_vencimento', $ano)->whereStatus('Pago')
        );

        // FINANCEIRO - A PAGAR (Soma de VALORES por Status)
        $pagarStats = $getSumStats(
            ContasPagar::whereMonth('created_at', $mes)->whereYear('created_at', $ano)->whereStatus('Pago')
        );

        // SOLICITAÇÕES (Quantidade por Status)
        $solicitacoesStats = $getStats(
            Solicitacao::whereMonth('data_solicitacao', $mes)->whereYear('data_solicitacao', $ano)
        );

        // 3. Gráfico e Atividades (Mantido igual)
        $receitasDoMes = ContasReceber::where('status', 'Pago')
            ->whereMonth('data_vencimento', $mes)
            ->whereYear('data_vencimento', $ano)
            ->get();

        $receitasAgrupadas = $receitasDoMes->groupBy(function($item) {
            return $item->data_vencimento->format('d');
        })->map(function($dia) { return $dia->sum('valor'); });

        $labelsGrafico = [];
        $dadosGrafico = [];
        $diasNoMes = Carbon::createFromDate($ano, $mes, 1)->daysInMonth;

        for ($i = 1; $i <= $diasNoMes; $i++) {
            $diaStr = str_pad($i, 2, '0', STR_PAD_LEFT);
            $labelsGrafico[] = $diaStr . '/' . $mes;
            $dadosGrafico[] = $receitasAgrupadas->get($diaStr) ?? 0;
        }

        $atividades = Activity::latest()->take(7)->get();

        return view('index', compact(
            'atividades',
            'processosStats',
            'orcamentosStats',
            'prevStats',
            'corrStats',
            'receberStats',
            'pagarStats',
            'solicitacoesStats',
            'labelsGrafico',
            'dadosGrafico',
            'mes',
            'ano'
        ));
    }
}