@extends('layouts.main')

@section('title', 'Magserv | Editar Contrato')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Editar Contrato</h1>
            <p class="text-gray-600 mt-1">Altere os dados do contrato 
                <span class="font-semibold">{{ $contrato->numero_contrato }}</span>, 
                do cliente {{ $contrato->clientes->first()->nome ?? 'N/A' }}.
            </p>
        </div>
        <a href="{{ route('contratos.index') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-4 rounded-lg">
            Voltar para a Lista
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
            <p class="font-bold">Ocorreram erros de validação:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-8 rounded-lg shadow-md">
        <form action="{{ route('contratos.update', $contrato->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Clientes vinculados <span class="text-red-500">*</span></label>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 max-h-60 overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @php
                                $selected = old('clientes', $contrato->clientes->pluck('id')->toArray());
                            @endphp

                            @foreach($clientes as $cliente)
                                <div class="flex items-center bg-white p-2 rounded border border-gray-100 hover:border-purple-300 transition-colors">
                                    <input id="cliente_{{ $cliente->id }}" name="clientes[]" value="{{ $cliente->id }}" type="checkbox"
                                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded cursor-pointer"
                                        {{ (is_array($selected) && in_array($cliente->id, $selected)) ? 'checked' : '' }}>
                                    
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
                    <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-2">Data Início <span class="text-red-500">*</span></label>
                    <input type="date" id="data_inicio" name="data_inicio"
                           value="{{ old('data_inicio', $contrato->data_inicio ? $contrato->data_inicio->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    @error('data_inicio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-2">Data Fim</label>
                    <input type="date" id="data_fim" name="data_fim"
                           value="{{ old('data_fim', $contrato->data_fim ? $contrato->data_fim->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('data_fim') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="ativo" class="block text-sm font-medium text-gray-700 mb-2">Status do Contrato</label>
                    <select id="ativo" name="ativo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="1" {{ old('ativo', $contrato->ativo) == 1 ? 'selected' : '' }}>Ativo (Sim)</option>
                        <option value="0" {{ old('ativo', $contrato->ativo) == 0 ? 'selected' : '' }}>Inativo (Não)</option>
                    </select>
                    @error('ativo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end mt-10 pt-6 border-gray-200">
                <a href="{{ route('contratos.index') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-6 rounded-lg mr-4">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 text-white hover:bg-blue-700 font-medium py-2 px-6 rounded-lg">
                    Atualizar Contrato
                </button>
            </div>
        </form>
    </div>
@endsection