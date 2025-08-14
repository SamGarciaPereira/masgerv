<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/financeiro/contas-pagar', function () {
    return view('financeiro.contaPagar');
})->name('financeiro.contas-pagar');

Route::get('/financeiro/contas-receber', function () {
    return view('financeiro.contaReceber');
})->name('financeiro.contas-receber');

Route::get('/orcamentos', function () {
    return view('orcamento.orcamento');
})->name('orcamentos');

Route::get('/manutencao', function () {
    return view('manutencao.manutencao');
})->name('manutencao');

Route::get('/processo', function () {
    return view('processo.processo');
})->name('processo' );

Route::get('/auth/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/auth/cadastrar', function () {
    return view('auth.cadastrar');
})->name('cadastrar');
