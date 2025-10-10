<?php

namespace App\Http\Controllers\financeiro;

use App\Http\Controllers\Controller;
use App\Models\ContasReceber;
use App\Models\Cliente;
use App\Models\Processo;
use Illuminate\Http\Request;

class ContasReceberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contasReceber = ContasReceber::with(['cliente', 'processo.orcamento'])->latest()->get();
        return view('financeiro.contas-receber.index', compact('contasReceber'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::orderBy('nome')->get();
        return view('financeiro.contas-receber.create', compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0.01',
            'data_vencimento' => 'required|date',
            'status' => 'required|in:Pendente,Pago,Atrasado',
        ]);

        ContasReceber::create($validatedData);

        return redirect()->route('financeiro.contas-receber.index')
                         ->with('success', 'Conta a receber cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Geralmente não é necessário para CRUDs simples, pode deixar vazio.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContasReceber $contasReceber)
    {
        $clientes = Cliente::orderBy('nome')->get();
        return view('financeiro.contas-receber.edit', compact('contasReceber', 'clientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContasReceber $contasReceber)
    {
        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0.01',
            'data_vencimento' => 'required|date',
            'status' => 'required|in:Pendente,Pago,Atrasado',
        ]);

        $contasReceber->update($validatedData);

        return redirect()->route('financeiro.contas-receber.index')
                         ->with('success', 'Conta a receber atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContasReceber $contasReceber)
    {
        $contasReceber->delete();

        return redirect()->route('financeiro.contas-receber.index')
                         ->with('success', 'Conta a receber removida com sucesso!');
    }
}