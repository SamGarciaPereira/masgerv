<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\index\DashboardController;
use App\Http\Controllers\cliente\ClienteController;
use App\Http\Controllers\orcamento\OrcamentoController;
use App\Http\Controllers\processo\ProcessoController;
use App\Http\Controllers\manutencao\ManutencaoController;
use App\Http\Controllers\financeiro\ContasPagarController;
use App\Http\Controllers\financeiro\ContasReceberController;
use App\Http\Controllers\AuthController;

// --- ROTA PRINCIPAL ---
Route::get('/', [DashboardController::class, 'index'])->name('home');

// --- ROTAS DOS MÓDULOS (CRUD) ---
// O comando 'resource' cria automaticamente as rotas (index, create, store, etc.)
Route::resource('clientes', ClienteController::class);
Route::resource('orcamentos', OrcamentoController::class);
Route::resource('processos', ProcessoController::class);
Route::resource('manutencoes', ManutencaoController::class)
    ->only(['index', 'store', 'update', 'destroy']);

// --- ROTAS DO MÓDULO FINANCEIRO ---
Route::prefix('financeiro')->name('financeiro.')->group(function () {
    Route::resource('contas-pagar', ContasPagarController::class);
    Route::resource('contas-receber', ContasReceberController::class);
});

// ROTAS DO MÓDULO DE MANUTENÇÃO
Route::prefix('manutencoes/corretiva')->name('manutencoes.corretiva.')->group(function () {
    Route::get('/', [ManutencaoController::class, 'indexCorretiva'])->name('index');
    Route::get('/create', [ManutencaoController::class, 'createCorretiva'])->name('create');
    Route::get('/{manutencao}/edit', [ManutencaoController::class, 'editCorretiva'])->name('edit');
});

Route::prefix('manutencoes/preventiva')->name('manutencoes.preventiva.')->group(function () {
    Route::get('/', [ManutencaoController::class, 'indexPreventiva'])->name('index');
    Route::get('/create', [ManutencaoController::class, 'createPreventiva'])->name('create');
    Route::get('/{manutencao}/edit', [ManutencaoController::class, 'editPreventiva'])->name('edit');
});

// --- ROTAS DE AUTENTICAÇÃO ---
Route::get('/login', function() { return 'Página de Login'; })->name('login');

