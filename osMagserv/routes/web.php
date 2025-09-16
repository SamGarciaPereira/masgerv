<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\index\DashboardController;
use App\Http\Controllers\cliente\ClienteController;
use App\Http\Controllers\orcamento\OrcamentoController;
use App\Http\Controllers\processo\ProcessoController;
use App\Http\Controllers\manutencao\ManutencaoController;
use App\Http\Controllers\financeiro\contasPagarController;
use App\Http\Controllers\financeiro\contasReceberController;
use App\Http\Controllers\authController;

// --- ROTA PRINCIPAL ---
Route::get('/', [DashboardController::class, 'index'])->name('home');

// --- ROTAS DOS MÓDULOS (CRUD) ---
// O comando 'resource' cria automaticamente as rotas (index, create, store, etc.)
Route::resource('clientes', ClienteController::class);
Route::resource('orcamentos', OrcamentoController::class);
Route::resource('processos', ProcessoController::class);
Route::resource('manutencoes', ManutencaoController::class);

// --- ROTAS DO MÓDULO FINANCEIRO ---
Route::resource('financeiro/contas-pagar', contasPagarController::class)
     ->names('financeiro.contas-pagar');
Route::resource('financeiro/contas-receber', contasReceberController::class)
     ->names('financeiro.contas-receber');

// --- ROTAS DE AUTENTICAÇÃO ---
Route::get('/login', function() { return 'Página de Login'; })->name('login');

