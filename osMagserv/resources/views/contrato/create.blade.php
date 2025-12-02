@extends('layouts.main')

@section('title', 'Magserv | Novo Contrato')

@section('content')

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Cadastrar novo contrato de manutenção</h1>
            <p class="text-gray-600 mt-1">Preencha os dados abaixo para cadastrar um novo contrato de manutenção.</p>
        </div>
        <a href="{{ route('contratos.index') }}"
            class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-4 rounded-lg">
            Voltar para a Lista
        </a>
    </div>

    <div class="bg-white p-8 rounded-lg shadow-md">
        <form action="{{ route('contratos.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-2">Cliente <span class="text-red-500">*</span></label>
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
                    <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-2">Data Início <span class="text-red-500">*</span></label>
                    <input type="date" id="data_inicio" name="data_inicio" value="{{ old('data_inicio') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    @error('data_inicio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-2">Data Fim</label>
                    <input type="date" id="data_fim" name="data_fim" value="{{ old('data_fim') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('data_fim') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
            </div>
            <div class="flex justify-end">
                <a href="{{ route('contratos.index') }}"
                    class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-6 rounded-lg mr-4">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 text-white hover:bg-blue-700 font-medium py-2 px-6 rounded-lg">
                    Salvar Contrato
                </button>
            </div>
        </form>
    </div>
    


@endsection