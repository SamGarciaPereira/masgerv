<?php

namespace App\Http\Controllers\index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Activity;
use App\Models\Processo;
use App\Models\Orcamento;
use App\Models\Manutencao;
use App\Models\ContasReceber;
use App\Models\ContasPagar;
use App\Models\Solicitacao;

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

        $processosStats = $getStats(Processo::whereMonth('created_at', $mes)->whereYear('created_at', $ano));
        $orcamentosStats = $getStats(Orcamento::whereMonth('created_at', $mes)->whereYear('created_at', $ano));
        
        $prevStats = $getStats(Manutencao::where('tipo', 'Preventiva')
            ->whereMonth('data_inicio_atendimento', $mes)->whereYear('data_inicio_atendimento', $ano));
            
        $corrStats = $getStats(Manutencao::where('tipo', 'Corretiva')
            ->whereMonth('data_inicio_atendimento', $mes)->whereYear('data_inicio_atendimento', $ano));

        $receberStats = $getSumStats(ContasReceber::whereMonth('data_vencimento', $mes)->whereYear('data_vencimento', $ano));
        $pagarStats = $getSumStats(ContasPagar::whereMonth('data_vencimento', $mes)->whereYear('data_vencimento', $ano));
        
        $solicitacoesStats = $getStats(Solicitacao::whereMonth('data_solicitacao', $mes)->whereYear('data_solicitacao', $ano));

        $diasNoMes = Carbon::createFromDate($ano, $mes, 1)->daysInMonth;
        $labelsGrafico = [];
        
        for ($i = 1; $i <= $diasNoMes; $i++) {
            $labelsGrafico[] = str_pad($i, 2, '0', STR_PAD_LEFT) . '/' . $mes;
        }

        $getDailyStatusData = function($modelClass) use ($mes, $ano, $diasNoMes) {
            $contas = $modelClass::whereMonth('data_vencimento', $mes)
                ->whereYear('data_vencimento', $ano)
                ->get();

            $pago = array_fill(0, $diasNoMes, 0);
            $pendente = array_fill(0, $diasNoMes, 0);
            $atrasado = array_fill(0, $diasNoMes, 0);

            foreach ($contas as $conta) {
                $diaIndex = (int)$conta->data_vencimento->format('d') - 1;
                
                $status = ucfirst(strtolower(trim($conta->status))); 

                if ($status === 'Pago' || $status === 'Concluída' || $status === 'Finalizado') {
                    $pago[$diaIndex] += $conta->valor;
                } 
                elseif ($status === 'Atrasado' || $status === 'Vencido') {
                    $atrasado[$diaIndex] += $conta->valor;
                } 
                else {
                    $pendente[$diaIndex] += $conta->valor;
                }
            }

            return compact('pago', 'pendente', 'atrasado');
        };

        $dadosReceita = $getDailyStatusData(ContasReceber::class);
        $dadosDespesa = $getDailyStatusData(ContasPagar::class);

        $atividades = Activity::latest()->take(7)->get();

        return view('index', compact(
            'atividades', 'processosStats', 'orcamentosStats', 'prevStats', 
            'corrStats', 'receberStats', 'pagarStats', 'solicitacoesStats',
            'labelsGrafico', 'dadosReceita', 'dadosDespesa',
            'mes', 'ano'
        ));
    }
}