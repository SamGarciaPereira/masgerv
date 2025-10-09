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
        $contasReceber = ContasReceber::with(['cliente', 'processo'])->latest()->get();
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
            'processo_id' => 'nullable|exists:processos,id',
            'cliente_id' => 'required|exists:clientes,id',
            'descricao' => 'required|string|max:255',
            'nf' => 'nullable|string|max:100',
            'valor' => 'required|numeric|min:0',
            'data_vencimento' => 'required|date',
            'status' => 'required|in:Pendente,Pago,Cancelado',
        ]);
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
    public function edit(string $id)
    {
        return view('financeiro.contas-receber.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
