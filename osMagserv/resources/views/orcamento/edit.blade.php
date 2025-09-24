@extends('layouts.main')

@section('content')

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Editar Orçamento</h1>
            <p class="text-gray-600 mt-1">Altere os dados da proposta Nº {{ $orcamento->numero_proposta }}.</p>
        </div>
        <a href="{{ route('orcamentos.index') }}"
            class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-4 rounded-lg">
            Voltar para a Lista
        </a>
    </div>

    <div class="bg-white p-8 rounded-lg shadow-md">
        <form action="{{ route('orcamentos.update', $orcamento->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-2">Cliente</label>
                    <select id="cliente_id" name="cliente_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                        <option value="">Selecione um cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ $orcamento->cliente_id == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="numero_proposta" class="block text-sm font-medium text-gray-700 mb-2">Nº da Proposta</label>
                    <input type="text" id="numero_proposta" name="numero_proposta" value="{{ $orcamento->numero_proposta }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                <div>
                    <label for="data_envio" class="block text-sm font-medium text-gray-700 mb-2">Data de Envio</label>
                    <input type="date" id="data_envio" name="data_envio" value="{{ $orcamento->data_envio }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                <div>
                    <label for="valor" class="block text-sm font-medium text-gray-700 mb-2">Valor (R$)</label>
                    <input type="number" step="0.01" id="valor" name="valor" value="{{ $orcamento->valor }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                <div class="md:col-span-2">
                    <label for="escopo" class="block text-sm font-medium text-gray-700 mb-2">Escopo / Descrição</label>
                    <textarea id="escopo" name="escopo" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>{{ $orcamento->escopo }}</textarea>
                </div>
                <div>
                    <label for="revisao" class="block text-sm font-medium text-gray-700 mb-2">Revisão</label>
                    <input type="number" id="revisao" name="revisao" value="{{ $orcamento->revisao }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
            </div>

            <div class="flex justify-end mt-10 pt-6 border-t">
                <a href="{{ route('orcamentos.index') }}"
                    class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-6 rounded-lg mr-4">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 text-white hover:bg-blue-700 font-medium py-2 px-6 rounded-lg">
                    Atualizar Orçamento
                </button>
            </div>
        </form>
    </div>

@endsection