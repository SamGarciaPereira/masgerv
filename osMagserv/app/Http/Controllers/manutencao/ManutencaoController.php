<?php

namespace App\Http\Controllers\manutencao;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Manutencao;
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::orderBy('nome')->get();
        return view('manutencao.create', compact('clientes'));
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
    public function show(Manutencao $manutencao)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Manutencao $manutencao)
    {
        $clientes = Cliente::orderBy('nome')->get();
        return view('manutencao.edit', compact('manutencao', 'clientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Manutencao $manutencao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Manutencao $manutencao)
    {
        $manutencao->delete();
        return redirect()->route('manutencao.index')->with('success', 'Manutenção agendada removida com sucesso!');
    }
}
