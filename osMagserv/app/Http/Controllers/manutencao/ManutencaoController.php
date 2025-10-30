<?php

namespace App\Http\Controllers\manutencao;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Manutencao;
use App\Models\Orcamento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ManutencaoController extends Controller
{
    public function index()
    {
        $manutencoes = Manutencao::with('cliente')->latest()->get();
        return view('manutencao.index', compact('manutencoes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'tipo' => ['required', Rule::in(['Preventiva', 'Corretiva'])],
            'chamado' => 'nullable|string|max:255',
            'solicitante' => 'required|string|max:255',
            'descricao' => 'required|string',
            'data_inicio_atendimento' => 'required|date',
            'data_fim_atendimento' => 'nullable|date|after_or_equal:data_inicio_atendimento',
            'status' => ['required', Rule::in(['Agendada', 'Em Andamento', 'Concluída', 'Cancelada'])],
        ]);

        if ($validatedData['tipo'] === 'Preventiva') {
            $validatedData['chamado'] = null;
        }

        Manutencao::create($validatedData);

        if ($validatedData['tipo'] === 'Corretiva') {
             return redirect()->route('manutencoes.corretiva.index')
                 ->with('success', 'Manutenção corretiva agendada com sucesso!');
        } else {
             return redirect()->route('manutencoes.preventiva.index')
                 ->with('success', 'Manutenção preventiva agendada com sucesso!');
        }
    }

    public function show(Manutencao $manutencao)
    {
        abort(404);
    }

    public function update(Request $request, Manutencao $manutencao)
    {
        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'tipo' => ['required', Rule::in(['Preventiva', 'Corretiva'])], 
            'chamado' => 'nullable|string|max:255',
            'solicitante' => 'nullable|string|max:255', 
            'descricao' => 'required|string',
            'data_inicio_atendimento' => 'required|date',
            'data_fim_atendimento' => 'nullable|date|after_or_equal:data_inicio_atendimento',
            'status' => ['required', Rule::in(['Agendada', 'Em Andamento', 'Concluída', 'Cancelada'])],
        ]);

        $manutencao->update($validatedData);

       // $updateResult = $manutencao->update($validatedData);

        
        //dd($updateResult, $validatedData, $manutencao->getChanges());

        if ($validatedData['tipo'] === 'Corretiva') {
             return redirect()->route('manutencoes.corretiva.index')
                 ->with('success', 'Manutenção corretiva atualizada com sucesso!');
        } else {
             return redirect()->route('manutencoes.preventiva.index')
                 ->with('success', 'Manutenção preventiva atualizada com sucesso!');
        }
    }


    public function destroy(Manutencao $manutencao)
    {
        $tipo = $manutencao->tipo;
        $manutencao->delete();

        if ($tipo === 'Corretiva') {
            return redirect()->route('manutencoes.corretiva.index')->with('success', 'Manutenção corretiva removida com sucesso!');
        } else {
             return redirect()->route('manutencoes.preventiva.index')->with('success', 'Manutenção preventiva removida com sucesso!');
        }
    }

    public function createOrcamento(Manutencao $manutencao)
    {
        if($manutencao->tipo !== 'Corretiva' || !$manutencao->chamado) {
            return redirect()->back()->with('error', 'Só é possível gerar orçamento a partir de manutenções corretivas com chamado.');
        }

        $orcamento = Orcamento::create([
            'cliente_id' => $manutencao->cliente_id,
            'escopo' => "Orçamento referente a manutenção do chamado: {$manutencao->chamado}\n\n{$manutencao->descricao}",
            'status' => 'Pendente',
        ]);

        return redirect()->route('orcamentos.edit', $orcamento)
            ->with('success', 'Orçamento gerado a partir da manutenção. Por favor, preencha o restante das informações.');
    }

    public function indexCorretiva()
    {
        $manutencoes = Manutencao::where('tipo', 'Corretiva')
                                ->with('cliente')
                                ->orderBy('created_at', 'desc')
                                ->get();
        return view('manutencao.manutencao-corretiva.index', compact('manutencoes'));
    }

    public function createCorretiva()
    {
        $clientes = Cliente::all();
        return view('manutencao.manutencao-corretiva.create', compact('clientes'));
    }

    public function editCorretiva(Manutencao $manutencao)
    {
        if ($manutencao->tipo !== 'Corretiva') {
            abort(404);
        }
        $clientes = Cliente::all();
        return view('manutencao.manutencao-corretiva.edit', compact('manutencao', 'clientes'));
    }

    public function indexPreventiva()
    {
        $manutencoes = Manutencao::where('tipo', 'Preventiva')
                                ->with('cliente')
                                ->orderBy('created_at', 'desc')
                                ->get();
        return view('manutencao.manutencao-preventiva.index', compact('manutencoes'));
    }

    public function createPreventiva()
    {
        $clientes = Cliente::all();
        return view('manutencao.manutencao-preventiva.create', compact('clientes'));
    }

    public function editPreventiva(Manutencao $manutencao)
    {
        if ($manutencao->tipo !== 'Preventiva') {
             abort(404);
        }
        $clientes = Cliente::all();
        return view('manutencao.manutencao-preventiva.edit', compact('manutencao', 'clientes'));
    }
}