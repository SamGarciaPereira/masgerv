@extends('layouts.main')

@section('title', 'Magserv | Editar Conta')

@section('content')

<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Editar Conta a Pagar</h1>
        <p class="text-gray-600 mt-1">Atualize as informações do lançamento.</p>
    </div>
    <a href="{{ route('financeiro.contas-pagar.index') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-4 rounded-lg">
        Voltar para a Lista
    </a>
</div>

<div class="bg-white p-8 rounded-lg shadow-md">
    <form action="{{ route('financeiro.contas-pagar.update', $contasPagar->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            
            <div>
                <label for="fornecedor" class="block text-sm font-medium text-gray-700 mb-2">Fornecedor <span class="text-red-500">*</span></label>
                <input type="text" id="fornecedor" name="fornecedor" value="{{ old('fornecedor', $contasPagar->fornecedor) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição <span class="text-red-500">*</span></label>
                <input type="text" id="descricao" name="descricao" value="{{ old('descricao', $contasPagar->descricao) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label for="danfe" class="block text-sm font-medium text-gray-700 mb-2">DANFE</label>
                <input type="text" id="danfe" name="danfe" value="{{ old('danfe', $contasPagar->danfe) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="valor" class="block text-sm font-medium text-gray-700 mb-2">Valor (R$) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" id="valor" name="valor" value="{{ old('valor', $contasPagar->valor) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="Pendente" {{ old('status', $contasPagar->status) == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="Pago" {{ old('status', $contasPagar->status) == 'Pago' ? 'selected' : '' }}>Pago</option>
                    <option value="Atrasado" {{ old('status', $contasPagar->status) == 'Atrasado' ? 'selected' : '' }}>Atrasado</option>
                </select>
            </div>

            <div>
                <label for="tipo_recorrencia_display" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Lançamento <span class="text-gray-400">(Fixo)</span></label>
                <select id="tipo_recorrencia_display" class="w-full px-4 py-2 border border-gray-300 bg-gray-100 text-gray-600 rounded-lg cursor-not-allowed" disabled>
                    <option value="unica" {{ !$contasPagar->is_fixa ? 'selected' : '' }}>Conta Única / Parcela</option>
                    <option value="fixa" {{ $contasPagar->is_fixa ? 'selected' : '' }}>Conta Fixa (Mensal)</option>
                </select>
                <input type="hidden" id="tipo_recorrencia" value="{{ $contasPagar->is_fixa ? 'fixa' : 'unica' }}">
            </div>
        </div>

        <hr class="border-gray-200 my-6">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 bg-gray-50 p-6 rounded-lg border border-gray-200">
            <div id="div_dia_fixo" class="hidden">
                <label for="dia_fixo" class="block text-sm font-medium text-gray-700 mb-2">Dia do Vencimento <span class="text-red-500">*</span></label>
                <input type="number" id="dia_fixo" name="dia_fixo" min="1" max="31" 
                       value="{{ old('dia_fixo', optional($contasPagar->data_vencimento)->day) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-1">Alterar este dia atualizará o vencimento deste mês.</p>
            </div>

            <div id="div_data_vencimento">
                <label for="data_vencimento" class="block text-sm font-medium text-gray-700 mb-2">Data Vencimento <span class="text-red-500">*</span></label>
                <input type="date" id="data_vencimento" name="data_vencimento" 
                       value="{{ old('data_vencimento', optional($contasPagar->data_vencimento)->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div id="div_data_pagamento">
                <label for="data_pagamento" class="block text-sm font-medium text-gray-700 mb-2">Data Pagamento</label>
                <input type="date" id="data_pagamento" name="data_pagamento" 
                       value="{{ old('data_pagamento', optional($contasPagar->data_pagamento)->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div class="flex justify-end mt-10 pt-6">
            <a href="{{ route('financeiro.contas-pagar.index') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-6 rounded-lg mr-4">
                Cancelar
            </a>
            <button type="submit" class="bg-blue-600 text-white hover:bg-blue-700 font-medium py-2 px-6 rounded-lg">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

@vite('resources/js/app.js')

@endsection