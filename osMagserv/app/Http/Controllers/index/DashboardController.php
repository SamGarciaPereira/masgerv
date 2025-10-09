<?php

namespace App\Http\Controllers\index;

use App\Http\Controllers\Controller;
use App\Models\Activity; 
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $atividades = Activity::latest()->take(7)->get();

        return view('index', compact('atividades'));
    }

    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
