@extends('layouts.main')

@section('content')

<!-- Cabeçalho da Página -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-gray-600 mt-1">Bem-vindo de volta! Aqui está um resumo da sua operação.</p>
</div>

<!-- Cartões de Resumo (KPIs) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500">Processos Ativos</p>
            <p class="text-3xl font-bold"></p>
        </div>
        <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
            <i class="bi bi-inboxes-fill text-2xl"></i>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500">Orçamentos Pendentes</p>
            <p class="text-3xl font-bold"></p>
        </div>
        <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full">
            <i class="bi bi-file-earmark-ruled-fill text-2xl"></i>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500">Manutenções Agendadas</p>
            <p class="text-3xl font-bold"></p>
        </div>
        <div class="bg-green-100 text-green-600 p-3 rounded-full">
            <i class="bi bi-hammer text-2xl"></i>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500">Contas a Vencer (7 dias)</p>
            <p class="text-3xl font-bold"></p>
        </div>
        <div class="bg-red-100 text-red-600 p-3 rounded-full">
            <i class="bi bi-calendar-x-fill text-2xl"></i>
        </div>
    </div>
</div>

<!-- Seção de Gráfico e Atividades Recentes -->
<div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Gráfico (usando um placeholder) -->
    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
        <h3 class="font-bold text-lg mb-4">Fluxo de Caixa (Últimos 6 meses)</h3>
        <div class="flex items-center justify-center bg-gray-100 rounded-md h-64">
            <p class="text-gray-500">[Aqui entraria um gráfico de barras - ex: Chart.js]</p>
        </div>
    </div>
    <!-- Atividades Recentes -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="font-bold text-lg mb-4">Atividades Recentes</h3>
        <ul class="space-y-4">
            <li class="flex items-start">
                <div class="bg-blue-100 text-blue-600 p-2 rounded-full mr-3"><i class="bi bi-plus-lg"></i></div>
                <div>
                    <p class="font-medium"></p>
                    <p class="text-sm text-gray-500"></p>
                </div>
            </li>
            <li class="flex items-start">
                <div class="bg-green-100 text-green-600 p-2 rounded-full mr-3"><i class="bi bi-check-lg"></i></div>
                <div>
                    <p class="font-medium">.</p>
                    <p class="text-sm text-gray-500"></p>
                </div>
            </li>
            <li class="flex items-start">
                <div class="bg-yellow-100 text-yellow-600 p-2 rounded-full mr-3"><i class="bi bi-pencil-fill"></i></div>
                <div>
                    <p class="font-medium"></p>
                    <p class="text-sm text-gray-500"></p>
                </div>
            </li>
        </ul>
    </div>
</div>

@endsection

