<?php

namespace App\Http\Controllers\index;

use App\Http\Controllers\Controller;
use App\Models\Activity; // Importe o Model Activity
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Busca as 5 atividades mais recentes
        $atividades = Activity::latest()->take(5)->get();

        // Envia a variável $atividades para a view
        return view('index', compact('atividades'));
    }
}
