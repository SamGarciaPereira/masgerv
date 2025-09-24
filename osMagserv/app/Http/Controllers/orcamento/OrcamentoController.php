<?php

namespace App\Http\Controllers\orcamento;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Orcamento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrcamentoController extends Controller
{
    public function index()
    {
        $orcamentos = Orcamento::with('cliente')->latest()->get();
        return view('orcamento.index', compact('orcamentos'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nome')->get();
        return view('orcamento.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'numero_proposta' => 'required|string|max:255|unique:orcamentos',
            'data_envio' => 'nullable|date', // Já está correto
            'valor' => 'required|numeric|min:0',
            'status' => 'required|string|in:Pendente,Em Andamento,Enviado,Aprovado',
            'revisao' => 'required|integer|min:0',
            'escopo' => 'required|string',
            // Adicione aqui a validação para os outros campos de data se necessário
            'data_limite_envio' => 'nullable|date',
            'data_aprovacao' => 'nullable|date',
        ]);

        // Garante que datas vazias sejam salvas como NULL
        $validatedData['data_envio'] = $validatedData['data_envio'] ?? null;
        $validatedData['data_limite_envio'] = $validatedData['data_limite_envio'] ?? null;
        $validatedData['data_aprovacao'] = $validatedData['data_aprovacao'] ?? null;

        Orcamento::create($validatedData);

        return redirect()->route('orcamentos.index')
            ->with('success', 'Orçamento cadastrado com sucesso!');
    }

    public function edit(Orcamento $orcamento)
    {
        $clientes = Cliente::orderBy('nome')->get();
        return view('orcamento.edit', compact('orcamento', 'clientes'));
    }

    public function update(Request $request, Orcamento $orcamento)
    {
        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'numero_proposta' => ['required', 'string', 'max:255', Rule::unique('orcamentos')->ignore($orcamento->id)],
            'data_envio' => 'nullable|date',
            'valor' => 'required|numeric|min:0',
            'status' => 'required|string|in:Pendente,Em Andamento,Enviado,Aprovado',
            'revisao' => 'required|integer|min:0',
            'escopo' => 'required|string',
            'data_limite_envio' => 'nullable|date',
            'data_aprovacao' => 'nullable|date',
        ]);

        $validatedData['data_envio'] = $validatedData['data_envio'] ?? null;
        $validatedData['data_limite_envio'] = $validatedData['data_limite_envio'] ?? null;
        $validatedData['data_aprovacao'] = $validatedData['data_aprovacao'] ?? null;

        $orcamento->update($validatedData);

        return redirect()->route('orcamentos.index')
            ->with('success', 'Orçamento atualizado com sucesso!');
    }

    public function destroy(Orcamento $orcamento)
    {
        $orcamento->delete();
        return redirect()->route('orcamentos.index')->with('success', 'Orçamento removido com sucesso!');
    }
}
