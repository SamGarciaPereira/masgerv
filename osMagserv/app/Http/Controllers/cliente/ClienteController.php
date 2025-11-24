<?php

namespace App\Http\Controllers\cliente;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::query();
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('responsavel', 'like', "%{$search}%")
                  ->orWhere('documento', 'like', "%{$search}%")
                  ->orWhere('telefone', 'like', "%{$search}%")
                  ->orWhere('cidade', 'like', "%{$search}%")
                  ->orWhere('estado', 'like', "%{$search}%");
            });
        }

        switch ($request->input('ordem')) {
            case 'antigos':
                $query->oldest();
                break;
            default: 
                $query->latest();
                break;
        }

        $clientes = $query->orderBy('nome')->paginate(10);
        return view('cliente.index', compact('clientes'));

        
        
    }

    public function create()
    {
        return view('cliente.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'documento' => 'nullable|string|max:20',
            'responsavel' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clientes,email',
            'telefone' => 'nullable|string|max:20',
            'cep' => 'nullable|string|max:10',
            'logradouro' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:20',
            'bairro' => 'nullable|string|max:100',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:50',
        ]);

        if (isset($validatedData['documento'])) {
        $validatedData['documento'] = preg_replace('/[^0-9]/', '', $validatedData['documento']);
    }

        Cliente::create($validatedData);

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente cadastrado com sucesso!');
    }

    /**
     * Mostra o formulário para editar um cliente.
     */
    public function edit(Cliente $cliente)
    {
        return view('cliente.edit', compact('cliente'));
    }

    /**
     * Atualiza o cliente no banco de dados.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'documento' => 'nullable|string|max:20',
            'responsavel' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clientes,email,' . $cliente->id,
            'telefone' => 'nullable|string|max:20',
            'cep' => 'nullable|string|max:10',
            'logradouro' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:20',
            'bairro' => 'nullable|string|max:100',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:50',
        ]);

        if (isset($validatedData['documento'])) {
        $validatedData['documento'] = preg_replace('/[^0-9]/', '', $validatedData['documento']);
    }

        $cliente->update($validatedData);

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente atualizado com sucesso!');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente removido com sucesso!');
    }
}
