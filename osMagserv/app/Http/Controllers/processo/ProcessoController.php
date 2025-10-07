<?php

namespace App\Http\Controllers\processo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Processo;

class ProcessoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $processos = Processo::with('orcamento.cliente')->latest()->get();
        return view('processo.index', compact('processos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(Processo $processo)
    {
        return view('processo.edit', compact('processo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:Em Aberto,Finalizado,Faturado',
            'numero_nota_fiscal' => 'nullable|string|max:255',
        ]);

        $processo->update($validatedData);

        return redirect()->route('processos.index')->with('success', 'Status do processo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
