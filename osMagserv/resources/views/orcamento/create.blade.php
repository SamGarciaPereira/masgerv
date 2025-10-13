@extends('layouts.main')

@section('title', 'Novo Orçamento')

@section('content')

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Cadastrar Novo Orçamento</h1>
            <p class="text-gray-600 mt-1">Preencha os dados abaixo para adicionar uma nova proposta.</p>
        </div>
        <a href="{{ route('orcamentos.index') }}"
            class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-4 rounded-lg">
            Voltar para a Lista
        </a>
    </div>

    <div class="bg-white p-8 rounded-lg shadow-md">
        <form action="{{ route('orcamentos.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-2">Cliente</label>
                    <select id="cliente_id" name="cliente_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                        <option value="">Selecione um cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nome }}</option>
                        @endforeach
                    </select>
                    @error('cliente_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="numero_proposta" class="block text-sm font-medium text-gray-700 mb-2">Nº da Proposta</label>
                    <input type="text" id="numero_proposta" name="numero_proposta" value="{{ old('numero_proposta') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('numero_proposta') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="data_envio" class="block text-sm font-medium text-gray-700 mb-2">Data de Envio</label>
                    <input type="date" id="data_envio" name="data_envio" value="{{ old('data_envio') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('data_envio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="data_limite_envio" class="block text-sm font-medium text-gray-700 mb-2">Data Limite para
                        Resposta</label>
                    <input type="date" id="data_limite_envio" name="data_limite_envio"
                        value="{{ old('data_limite_envio') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="data_aprovacao" class="block text-sm font-medium text-gray-700 mb-2">Data de
                        Aprovação</label>
                    <input type="date" id="data_aprovacao" name="data_aprovacao" value="{{ old('data_aprovacao') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="valor" class="block text-sm font-medium text-gray-700 mb-2">Valor (R$)</label>
                    <input type="number" step="0.01" id="valor" name="valor" value="{{ old('valor') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Ex: 1500.50">
                    @error('valor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                        <option value="Pendente" {{ old('status') == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                        <option value="Em Andamento" {{ old('status') == 'Em Andamento' ? 'selected' : '' }}>Em Andamento
                        </option>
                        <option value="Enviado" {{ old('status') == 'Enviado' ? 'selected' : '' }}>Enviado</option>
                        <option value="Aprovado" {{ old('status') == 'Aprovado' ? 'selected' : '' }}>Aprovado</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="revisao" class="block text-sm font-medium text-gray-700 mb-2">Revisão</label>
                    <input type="number" id="revisao" name="revisao" value="{{ old('revisao', 0) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('revisao') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="lg:col-span-3">
                    <label for="escopo" class="block text-sm font-medium text-gray-700 mb-2">Escopo / Descrição</label>
                    <textarea id="escopo" name="escopo" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('escopo') }}</textarea>
                    @error('escopo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end mt-10 pt-6">
                <a href="{{ route('orcamentos.index') }}"
                    class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-6 rounded-lg mr-4">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 text-white hover:bg-blue-700 font-medium py-2 px-6 rounded-lg">
                    Salvar Orçamento
                </button>
            </div>
        </form>
    </div>

@endsection