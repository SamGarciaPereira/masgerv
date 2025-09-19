<?php

namespace App\Http\Controllers\index;

use App\Http\Controllers\Controller;
use App\Models\Activity; // 1. Importar o Model de Atividades
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 2. Buscar as 5 atividades mais recentes do banco de dados
        $atividades = Activity::latest()->take(5)->get();

        // 3. Enviar a variável $atividades para a view
        return view('index', compact('atividades'));
    }

    // Manter os outros métodos vazios por enquanto
    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
