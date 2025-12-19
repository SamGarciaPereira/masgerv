@extends('layouts.main')

@section('title', 'Magserv | Nova Conta a Pagar')

@section('content')

<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Cadastrar Nova Conta a Pagar</h1>
        <p class="text-gray-600 mt-1">Preencha os dados para adicionar uma nova conta a pagar.</p>
    </div>
    <a href="{{ route('financeiro.contas-pagar.index') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-4 rounded-lg">
        Voltar para a Lista
    </a>
</div>

<div class="bg-white p-8 rounded-lg shadow-md">
    <form action="{{ route('financeiro.contas-pagar.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <div>
                <label for="fornecedor" class="block text-sm font-medium text-gray-700 mb-2">Fornecedor <span class="text-red-500">*</span></label>
                <input type="text" id="fornecedor" name="fornecedor" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            
            <div>
                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição <span class="text-red-500">*</span></label>
                <input type="text" id="descricao" name="descricao" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label for="danfe" class="block text-sm font-medium text-gray-700 mb-2">DANFE</label>
                <input type="text" id="danfe" name="danfe" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="valor" class="block text-sm font-medium text-gray-700 mb-2">Valor (R$) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" id="valor" name="valor" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="Pendente" selected>Pendente</option>
                    <option value="Pago">Pago</option>
                    <option value="Atrasado">Atrasado</option>
                </select>
            </div>

            <div>
                <label for="tipo_recorrencia" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Lançamento <span class="text-red-500">*</span></label>
                <select id="tipo_recorrencia" name="tipo_recorrencia" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="unica" selected>Conta Única</option>
                    <option value="parcelada">Conta Parcelada</option>
                    <option value="fixa">Conta Fixa (Mensal)</option>
                </select>
            </div>
        </div>

        <hr class="border-gray-200 my-6">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 bg-gray-50 p-6 rounded-lg border border-gray-200">
            
            <div id="div_qtd_parcelas" class="hidden">
                <label for="qtd_parcelas" class="block text-sm font-medium text-gray-700 mb-2">Qtd. Parcelas <span class="text-red-500">*</span></label>
                <input type="number" id="qtd_parcelas" name="qtd_parcelas" min="2" max="120" value="2" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div id="div_dia_fixo" class="hidden">
                <label for="dia_fixo" class="block text-sm font-medium text-gray-700 mb-2">Dia do Vencimento (Todo mês) <span class="text-red-500">*</span></label>
                <input type="number" id="dia_fixo" name="dia_fixo" min="1" max="31" placeholder="Ex: 10" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-1">Repetirá este dia até Dezembro.</p>
            </div>

            <div id="div_data_vencimento">
                <label for="data_vencimento" id="label_vencimento" class="block text-sm font-medium text-gray-700 mb-2">Data Vencimento <span class="text-red-500">*</span></label>
                <input type="date" id="data_vencimento" name="data_vencimento" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div id="div_data_pagamento">
                <label for="data_pagamento" class="block text-sm font-medium text-gray-700 mb-2">Data Pagamento</label>
                <input type="date" id="data_pagamento" name="data_pagamento" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <p id="aviso_pagamento_parcial" class="hidden text-xs text-orange-600 mt-1 font-bold">Atenção: Será aplicado apenas à 1ª parcela.</p>
            </div>
        </div>

        <div class="flex justify-end mt-10 pt-6">
            <a href="{{ route('financeiro.contas-pagar.index') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-6 rounded-lg mr-4">
                Cancelar
            </a>
            <button type="submit" class="bg-blue-600 text-white hover:bg-blue-700 font-medium py-2 px-6 rounded-lg">
                Salvar Conta
            </button>
        </div>
    </form>
</div>

@vite('resources/js/app.js')
@endsection