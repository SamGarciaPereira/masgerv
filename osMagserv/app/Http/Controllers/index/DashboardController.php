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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Filtro de Data
        $mes = (int) $request->input('mes', now()->month);
        $ano = (int) $request->input('ano', now()->year);
        
        // --- FUNÇÕES AUXILIARES PARA ESTATÍSTICAS ---
        
        // Retorna array com contagem por status: ['Pendente' => 5, 'Concluída' => 2]
        $getStats = function($query) {
            return $query->groupBy('status')
                         ->pluck(DB::raw('count(*) as total'), 'status')
                         ->toArray();
        };
        
        // Retorna array com soma de valores por status: ['Pago' => 1500.00, 'Pendente' => 500.00]
        $getSumStats = function($query) {
            return $query->groupBy('status')
                         ->pluck(DB::raw('sum(valor) as total'), 'status')
                         ->toArray();
        };

        // 2. KPIs - Consultas Detalhadas (Filtradas por Mês e Ano)

        // PROCESSOS
        $processosStats = $getStats(
            Processo::whereMonth('created_at', $mes)->whereYear('created_at', $ano)
        );

        // ORÇAMENTOS
        $orcamentosStats = $getStats(
            Orcamento::whereMonth('created_at', $mes)->whereYear('created_at', $ano)
        );

        // MANUTENÇÕES (Preventivas) - Filtra pela data de início do atendimento
        $prevStats = $getStats(
            Manutencao::where('tipo', 'Preventiva')
                ->whereMonth('data_inicio_atendimento', $mes)
                ->whereYear('data_inicio_atendimento', $ano)
        );
        
        // MANUTENÇÕES (Corretivas) - Filtra pela data de início do atendimento
        $corrStats = $getStats(
            Manutencao::where('tipo', 'Corretiva')
                ->whereMonth('data_inicio_atendimento', $mes)
                ->whereYear('data_inicio_atendimento', $ano)
        );

        // FINANCEIRO (A Receber) - Filtra pela data de vencimento
        $receberStats = $getSumStats(
            ContasReceber::whereMonth('data_vencimento', $mes)->whereYear('data_vencimento', $ano)
        );

        // FINANCEIRO (A Pagar) - Filtra pela data de vencimento
        $pagarStats = $getSumStats(
            ContasPagar::whereMonth('data_vencimento', $mes)->whereYear('data_vencimento', $ano)
        );

        // SOLICITAÇÕES
        $solicitacoesStats = $getStats(
            Solicitacao::whereMonth('data_solicitacao', $mes)->whereYear('data_solicitacao', $ano)
        );

        // 3. DADOS PARA O GRÁFICO (RECEITA vs DESPESA DIÁRIA)
        $diasNoMes = Carbon::createFromDate($ano, $mes, 1)->daysInMonth;
        $labelsGrafico = [];
        
        // Preenche labels (dias do mês: 01/11, 02/11...)
        for ($i = 1; $i <= $diasNoMes; $i++) {
            $labelsGrafico[] = str_pad($i, 2, '0', STR_PAD_LEFT) . '/' . $mes;
        }

        // Função Helper para buscar dados diários de qualquer Model financeiro (apenas status 'Pago')
        $getDailyData = function($modelClass) use ($mes, $ano, $diasNoMes) {
            // Busca valores 'Pago' agrupados por dia
            $data = $modelClass::where('status', 'Pago')
                ->whereMonth('data_vencimento', $mes)
                ->whereYear('data_vencimento', $ano)
                ->get()
                ->groupBy(function($item) {
                    return $item->data_vencimento->format('d');
                })
                ->map(function($dia) { return $dia->sum('valor'); });

            // Preenche com 0 os dias vazios para manter o gráfico alinhado
            $result = [];
            for ($i = 1; $i <= $diasNoMes; $i++) {
                $dayStr = str_pad($i, 2, '0', STR_PAD_LEFT);
                $result[] = $data->get($dayStr) ?? 0;
            }
            return $result;
        };

        // Gera os dois arrays de dados para o Chart.js
        $dadosReceita = $getDailyData(ContasReceber::class);
        $dadosDespesa = $getDailyData(ContasPagar::class);

        // 4. Atividades Recentes
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
            'dadosReceita',
            'dadosDespesa',
            'mes',
            'ano'
        ));
    }
}