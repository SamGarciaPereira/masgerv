<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Solicitacao;
use App\Models\Manutencao;
use App\Models\Orcamento;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SolicitacaoController extends Controller
{
    public function index()
    {
        $solicitacoes = Solicitacao::where('status', 'pendente')->latest()->get();
        return view('admin.solicitacao.index', compact('solicitacoes'));
    }

    /**
     * Aprova uma solicitação e cria o registro na tabela correta.
     */
    public function accept(Solicitacao $solicitacao)
    {
        $dados = $solicitacao->dados;

        try {
            switch ($solicitacao->tipo) {
                
                case 'manutencao_corretiva':
                    $dados['data_inicio_atendimento'] = $dados['data_inicio_atendimento'] ?? Carbon::now();
                    Manutencao::create($dados);
                    break;
                
                case 'orcamento':
                    $dados['status'] = 'Pendente';
                    Orcamento::create($dados);
                    break;
            }

            if (empty($solicitacao->data_solicitacao)) {
                $solicitacao->data_solicitacao = $solicitacao->created_at ?? Carbon::now();
            }

            $solicitacao->status = 'Aceita';
            $solicitacao->data_resolucao = now(); 
            $solicitacao->save();

            return redirect()->route('admin.solicitacao.index') 
                             ->with('success', 'Solicitação aceita com sucesso!');

        } catch (\Exception $e) {
            return redirect()->route('admin.solicitacao.index') 
                             ->with('error', 'Erro ao aceitar solicitação: ' . $e->getMessage());
        }
    }

    
    //Recusa uma solicitação
    public function reject(Solicitacao $solicitacao)
    {       
        $solicitacao->status = 'Recusada';
        $solicitacao->data_resolucao = now(); 
        $solicitacao->save();
                
        return redirect()->route('admin.solicitacao.index')
                         ->with('success', 'Solicitação recusada.');
    }
}