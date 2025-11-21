<?php

namespace App\Http\Controllers\index;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Processo;
use App\Models\Orcamento;
use App\Models\Manutencao;
use App\Models\ContasReceber;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $mes = (int) $request->input('mes', now()->month);
        $ano = (int) $request->input('ano', now()->year);

        $dataFiltro = Carbon::createFromDate($ano, $mes, 1);

        $processosCount = Processo::whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->count();

        $orcamentosCount = Orcamento::whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->count();

        $manutencoesCount = Manutencao::whereMonth('data_inicio_atendimento', $mes)
            ->whereYear('data_inicio_atendimento', $ano)
            ->count();

        $totalReceber = ContasReceber::whereMonth('data_vencimento', $mes)
            ->whereYear('data_vencimento', $ano)
            ->where('status', 'Pendente') 
            ->sum('valor'); 
      
        $receitasDoMes = ContasReceber::where('status', 'Pago')
            ->whereMonth('data_vencimento', $mes)
            ->whereYear('data_vencimento', $ano)
            ->get();

        $receitasAgrupadas = $receitasDoMes->groupBy(function($item) {
            return $item->data_vencimento->format('d'); 
        })->map(function($dia) {
            return $dia->sum('valor');
        });

        $labelsGrafico = [];
        $dadosGrafico = [];
        $diasNoMes = $dataFiltro->daysInMonth;

        for ($i = 1; $i <= $diasNoMes; $i++) {
            $diaStr = str_pad($i, 2, '0', STR_PAD_LEFT); 
            $labelsGrafico[] = $diaStr . '/' . $mes;
            $dadosGrafico[] = $receitasAgrupadas->get($diaStr) ?? 0;
        }

        $atividades = Activity::latest()->take(7)->get();

        return view('index', compact(
            'atividades',
            'processosCount',
            'orcamentosCount',
            'manutencoesCount',
            'totalReceber',
            'labelsGrafico',
            'dadosGrafico',
            'mes',
            'ano'
        ));
    }
}