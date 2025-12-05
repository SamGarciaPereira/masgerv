@extends('layouts.main')

@section('title', 'Magserv | Nova Conta a Receber')

@section('content')

<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Cadastrar Nova Conta a Receber</h1>
        <p class="text-gray-600 mt-1">Preencha os dados para adicionar uma nova conta a receber.</p>
    </div>
    <a href="{{ route('financeiro.contas-receber.index') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-4 rounded-lg">
        Voltar para a Lista
    </a>
</div>

<div class="bg-white p-8 rounded-lg shadow-md">
    <form action="{{ route('financeiro.contas-receber.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div>
                <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-2">Cliente</label>
                <select id="cliente_id" name="cliente_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Selecione um cliente</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                    @endforeach 
                </select>
            </div>
            <div>
                <label for="valor" class="block text-sm font-medium text-gray-700 mb-2">Valor (R$)</label>
                <input type="number" step="0.01" id="valor" name="valor" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: 1500.50" required>
            </div>
            <div>
                <label for="nf" class="block text-sm font-medium text-gray-700 mb-2">NF</label>
                <input type="number" id="nf" name="nf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: 1700">
            </div>
            <div class="lg:col-span-3">
                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                <input type="text" id="descricao" name="descricao" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label for="data_vencimento" class="block text-sm font-medium text-gray-700 mb-2">Data de Vencimento</label>
                <input type="date" id="data_vencimento" name="data_vencimento" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="Pendente" {{ old('status') == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="Pago" {{ old('status') == 'Pago' ? 'selected' : '' }}>Pago</option>
                    <option value="Atrasado" {{ old('status') == 'Atrasado' ? 'selected' : '' }}>Atrasado</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end mt-10 pt-6">
            <a href="{{ route('financeiro.contas-receber.index') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-6 rounded-lg mr-4">
                Cancelar
            </a>
            <button type="submit" class="bg-blue-600 text-white hover:bg-blue-700 font-medium py-2 px-6 rounded-lg">
                Salvar Conta
            </button>
        </div>
    </form>
</div>

@endsection