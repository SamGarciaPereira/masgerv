<?php

namespace App\Http\Controllers\financeiro;

use App\Http\Controllers\Controller;
use App\Models\ContasPagar;
use Illuminate\Http\Request;

class ContasPagarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
      $query = ContasPagar::query();

      if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->where('descricao', 'like', "%{$search}%")
                ->orWhere('danfe', 'like', "%{$search}%")
                ->orWhere('fornecedor', 'like', "%{$search}%");

        });
      }

      if($request->filled('status')){
        $query->where('status', $request->input('status'));
      }

      switch ($request->input('ordem')) {
            case 'vencimento_asc':
                $query->orderBy('data_vencimento', 'asc');
                break;
            case 'vencimento_desc':
                $query->orderBy('data_vencimento', 'desc');
                break;
            case 'maior_valor':
                $query->orderByDesc('valor');
                break;
            case 'recentes':
                $query->latest();
                break;
            default:
                $query->orderBy('data_vencimento', 'asc');
                break;
        }

        $contasFixas = $query->clone()->where('fixa', true)->paginate(100, ['*'], 'page_fixas');
        $contasVariaveis = $query->clone()->where('fixa', false)->paginate(100, ['*'], 'page_variaveis');

        return view('financeiro.contas-pagar.index', compact('contasFixas', 'contasVariaveis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('financeiro.contas-pagar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fornecedor' => 'required|string|max:255',
            'descricao' => 'required|string|max:255',
            'danfe' => 'nullable|string|max:100',
            'valor' => 'required|numeric|min:0.01',
            'data_vencimento' => 'required|date',
            'data_pagamento' => 'required_if:status,Pago|nullable|date',
            'status' => 'required|in:Pendente,Pago,Atrasado',
            'fixa' => 'sometimes|boolean',
        ], [
            'data_pagamento.required_if' => 'A Data de Pagamento é obrigatória quando o status é Pago',
        ]);

        if(isset($validatedData['data_pagamento']) && $validatedData['data_pagamento'] !== null){
            $validatedData['status'] = 'Pago';
        }

        $validatedData['fixa'] = $request->boolean('fixa');

        ContasPagar::create($validatedData);

        return redirect()->route('financeiro.contas-pagar.index')
                         ->with('success', 'Conta a pagar cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContasPagar $contasPagar)
    {
        $contasPagar->load('anexos');
        return view('financeiro.contas-pagar.edit', compact('contasPagar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContasPagar $contasPagar)
    {
        $validatedData = $request->validate([
            'fornecedor' => 'required|string|max:255',
            'descricao' => 'required|string|max:255',
            'danfe' => 'nullable|string|max:100',
            'valor' => 'required|numeric|min:0.01',
            'data_vencimento' => 'required|date',
            'data_pagamento' => 'required_if:status,Pago|nullable|date',
            'status' => 'required|in:Pendente,Pago,Atrasado',
            'fixa' => 'sometimes|boolean',
        ],[
            'data_pagamento.required_if' => 'A Data de Pagamento é obrigatória quando o status é Pago',
        ]);     

        if(isset($validatedData['data_pagamento']) && $validatedData['data_pagamento'] !== null){
            $validatedData['status'] = 'Pago';
        }

        $validatedData['fixa'] = $request->boolean('fixa');

        $contasPagar->update($validatedData);

        return redirect()->route('financeiro.contas-pagar.index')
                         ->with('success', 'Conta a pagar atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContasPagar $contasPagar)
    {
        $contasPagar->delete();

        return redirect()->route('financeiro.contas-pagar.index')
                         ->with('success', 'Conta a pagar excluída com sucesso!');  
    }
}
