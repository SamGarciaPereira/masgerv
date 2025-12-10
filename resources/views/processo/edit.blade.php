@extends('layouts.main')

@section('title', 'Masgerv | Editar Processo')

@section('content')

<div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Editar Processo</h1>
            <p class="text-gray-600 mt-1">Altere os dados do processo vinculado à proposta Nº {{ $processo->orcamento->numero_proposta }}.</p>
        </div>
        <a href="{{ route('processos.index') }}"
            class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-4 rounded-lg">
            Voltar para a Lista
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
            <p class="font-bold">Atenção!</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-8 rounded-lg shadow-md">
        <form action="{{ route('processos.update', $processo->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label for="nf" class="block text-sm font-medium text-gray-700 mb-2">Número da NF</label>
                    <input type="text" id="nf" name="nf" value="{{ old('nf', $processo->nf) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('nf') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select id="status" name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        <option value="">Selecione um status</option>
                        required>
                        @foreach(['Em Aberto', 'Finalizado', 'Faturado'] as $status)
                            <option value="{{ $status }}" {{ old('status', $processo->status) == $status ? 'selected' : '' }}>
                                {{ $status }}</option>
                        @endforeach
                    </select>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex justify-end mt-10 pt-6">
            <a href="{{ route('processos.index') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-6 rounded-lg mr-4">
                Cancelar
            </a>
            <button type="submit" class="bg-blue-600 text-white    hover:bg-blue-700 font-medium py-2 px-6 rounded-lg">
                Atualizar Processo
            </button>
            </div>
        </form>
    </div>

@endsection