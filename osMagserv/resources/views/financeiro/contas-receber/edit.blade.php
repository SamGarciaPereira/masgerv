@extends('layouts.main')

@section('title', 'Magserv | Editar Conta a Receber')

@section('content')

<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Editar Conta a Receber</h1>
        <p class="text-gray-600 mt-1">Altere os dados da conta: {{ $contasReceber->descricao }}</p>
    </div>
    <a href="{{ route('financeiro.contas-receber.index') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-4 rounded-lg">
        Voltar para a Lista
    </a>
</div>

<div class="bg-white p-8 rounded-lg shadow-md">
    <form action="{{ route('financeiro.contas-receber.update', $contasReceber->id) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="cliente_id" value="{{ $contasReceber->cliente_id }}">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-3">
                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                <input type="text" id="descricao" name="descricao" value="{{ old('descricao', $contasReceber->descricao) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            
            <div>
                <label for="data_vencimento" class="block text-sm font-medium text-gray-700 mb-2">Data de Vencimento</label>
                <input type="date" id="data_vencimento" name="data_vencimento" value="{{ old('data_vencimento', optional($contasReceber->data_vencimento)->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label for="valor" class="block text-sm font-medium text-gray-700 mb-2">Valor (R$)</label>
                <input type="number" step="0.01" id="valor" name="valor" value="{{ old('valor', $contasReceber->valor) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="Pendente" {{ $contasReceber->status == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="Pago" {{ $contasReceber->status == 'Pago' ? 'selected' : '' }}>Pago</option>
                    <option value="Atrasado" {{ $contasReceber->status == 'Atrasado' ? 'selected' : '' }}>Atrasado</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end mt-10 pt-6">
            <a href="{{ route('financeiro.contas-receber.index') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-6 rounded-lg mr-4">
                Cancelar
            </a>
            <button type="submit" class="bg-blue-600 text-white hover:bg-blue-700 font-medium py-2 px-6 rounded-lg">
                Atualizar Conta
            </button>
        </div>
    </form>
</div>

@endsection