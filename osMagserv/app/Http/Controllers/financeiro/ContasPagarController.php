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
    public function index()
    {
        $contasPagar = ContasPagar::latest()->get();
        return view('financeiro.contas-pagar.index', compact('contasPagar'));
        
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
            'status' => 'required|in:Pendente,Pago,Atrasado',
        ]);

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
            'status' => 'required|in:Pendente,Pago,Atrasado',
        ]);     

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
