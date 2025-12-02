<?php

namespace App\Http\Controllers\contrato;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contrato;
use App\Models\Cliente;


class ContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Contrato::query();
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('cliente', function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%");
            })->orWhere('numero_contrato', 'like', "%{$search}%");    
        }
        $contratos = $query->with('cliente')->latest()->paginate(10);
        return view('contrato.index', compact('contratos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::orderBy('nome')->get();
        return view('contrato.create', compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'ativo' => '    boolean',
        ]);

        $contrato = Contrato::create($validatedData);

        return redirect()->route('contratos.index')
                         ->with('success', "Contrato '{$contrato->numero_contrato}' criado com sucesso.");
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
    public function edit(Contrato $contrato)
    {
        $contrato->load('anexos');
        $clientes = Cliente::orderBy('nome')->get();
        return view('contrato.edit', compact('contrato', 'clientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contrato $contrato)
    {
        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'ativo' => 'boolean',
        ]);

        $contrato->update($validatedData);

        return redirect()->route('contratos.index')
            ->with('success', 'Contrato atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contrato $contrato)
    {
        $contrato->delete();

        return redirect()->route('contratos.index')
                         ->with('success', "Contrato '{$contrato->numero_contrato}' excluído com sucesso.");
    }
}
