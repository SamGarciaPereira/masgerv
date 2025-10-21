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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $manutencoes = Manutencao::with('cliente')->latest()->get();
        return view('manutencao.index', compact('manutencoes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'tipo' => 'required|in:Corretiva,Preventiva',
            'chamado' => [
                Rule::requiredIf($request->input('tipo') === 'Corretiva'),
                'nullable',
                'string',
                'max:255'
            ],
            'descricao' => 'required|string',
            'data_inicio_agendamento' => 'required|date',
            'data_fim_agendamento' => 'required|date|after_or_equal:data_inicio_agendamento',
            'tipo' => ['required', Rule::in(['Preventiva', 'Corretiva'])],
            'status' => ['required', Rule::in(['Agendada', 'Em Andamento', 'Concluída', 'Cancelada'])],
        ]);

        Manutencao::create($validatedData);

        return redirect()->route('manutencoes.index')
            ->with('success', 'Manutenção agendada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Manutencao $manutencao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Manutencao $manutencao)
    {
        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'tipo' => 'required|in:Corretiva,Preventiva',
            'chamado' => [
                Rule::requiredIf($request->input('tipo') === 'Corretiva'),
                'nullable',
                'string',
                'max:255'
            ],
            'descricao' => 'required|string',
           'data_inicio_agendamento' => 'required|date',
            'data_fim_agendamento' => 'required|date|after_or_equal:data_inicio_agendamento',
            'tipo' => ['required', Rule::in(['Preventiva', 'Corretiva', 'Preditiva'])],
            'status' => ['required', Rule::in(['Agendada', 'Em Andamento', 'Concluída', 'Cancelada'])],
        ]);

        $data = $request->all();
        if ($data['tipo'] === 'Preventiva') {
            $data['chamado'] = null;
        }

        $manutencao->update($validatedData);

        return redirect()->route('manutencoes.index')
            ->with('success', 'Manutenção atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Manutencao $manutencao)
    {
        $manutencao->delete();
        return redirect()->route('manutencoes.index')->with('success', 'Manutenção agendada removida com sucesso!');
    }

    /**
     * Create an orcamento from a manutencao.
     */
    public function createOrcamento(Manutencao $manutencao)
    {
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
        $clientes = Cliente::all();
        return view('manutencao.manutencao-corretiva.edit', compact('manutencao', 'clientes'));
    }


    // --- MÉTODOS PARA PREVENTIVA ---

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
        $clientes = Cliente::all();
        return view('manutencao.manutencao-preventiva.edit', compact('manutencao', 'clientes'));
    }
}
