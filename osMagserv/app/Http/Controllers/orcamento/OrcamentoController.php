<?php

namespace App\Http\Controllers\orcamento;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Orcamento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrcamentoController extends Controller
{
    public function index(Request $request)
    {
        $query = Orcamento::with('cliente');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('numero_proposta', 'like', "%{$search}%")
                  ->orWhere('escopo', 'like', "%{$search}%") 
                  ->orWhereHas('cliente', function($q2) use ($search) {
                      $q2->where('nome', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%"); 
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        switch ($request->input('ordem')) {
            case 'antigos':
                $query->oldest();
                break;
            case 'maior_valor':
                $query->orderByDesc('valor');
                break;
            case 'menor_valor':
                $query->orderBy('valor');
                break;
            case 'aprovacao':
                $query->orderByDesc('data_aprovacao');
                break;
            default: 
                $query->latest();
                break;
        }

        $orcamentos = $query->paginate(10); 
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
            'numero_proposta' => 'nullable|string|max:255|unique:orcamentos',
            'data_envio' => 'nullable|date', 
            'valor' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:Pendente,Em Andamento,Enviado,Aprovado',
            'revisao' => 'nullable|integer|min:0',
            'escopo' => 'nullable|string',
            'data_limite_envio' => 'nullable|date',
            'data_aprovacao' => 'nullable|date',
        ]);

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
            'numero_proposta' => ['nullable', 'string', 'max:255', Rule::unique('orcamentos')->ignore($orcamento->id)],
            'data_envio' => 'nullable|date',
            'valor' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:Pendente,Em Andamento,Enviado,Aprovado',
            'revisao' => 'nullable|integer|min:0',
            'escopo' => 'nullable|string',
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
