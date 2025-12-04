@extends('layouts.main')

@section('title', 'Magserv | Novo Contrato')

@section('content')

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Novo Contrato de Manutenção</h1>
            <p class="text-gray-600 mt-1">Vincule uma Matriz. Todas as filiais herdarão este contrato automaticamente.</p>
        </div>
        <a href="{{ route('contratos.index') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-4 rounded-lg">
            Voltar
        </a>
    </div>

    <div class="bg-white p-8 rounded-lg shadow-md">
        <form action="{{ route('contratos.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Selecione a Matriz do Contrato <span class="text-red-500">*</span>
                    </label>
                    
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 max-h-60 overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($clientes as $cliente)
                                <div class="flex items-center bg-white p-2 rounded border border-gray-100 hover:border-purple-300 transition-colors">
                                    <input id="cliente_{{ $cliente->id }}" name="clientes[]" value="{{ $cliente->id }}" type="checkbox"
                                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded cursor-pointer"
                                        {{ (is_array(old('clientes')) && in_array($cliente->id, old('clientes'))) ? 'checked' : '' }}>
                                    
                                    <label for="cliente_{{ $cliente->id }}" class="ml-2 block text-sm font-semibold text-gray-800 cursor-pointer w-full">
                                        {{ $cliente->nome }}
                                        @if($cliente->filiais->count() > 0)
                                            <span class="block text-[10px] text-gray-500 font-normal">
                                                Cobre +{{ $cliente->filiais->count() }} filiais
                                            </span>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @error('clientes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-2">Início</label>
                    <input type="date" id="data_inicio" name="data_inicio" value="{{ old('data_inicio') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500" required>
                    @error('data_inicio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-2">Fim</label>
                    <input type="date" id="data_fim" name="data_fim" value="{{ old('data_fim') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500">
                    @error('data_fim') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
            </div>

            <div class="flex justify-end mt-8 pt-4 border-t border-gray-100">
                <button type="submit" class="bg-blue-600 text-white hover:bg-blue-700 font-medium py-2 px-6 rounded-lg shadow-sm">
                    Salvar Contrato
                </button>
            </div>
        </form>
    </div>

@endsection