@extends('layouts.main')

@section('title', 'Editar Manutenção Corretiva')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Editar Manutenção Corretiva</h1>
            <p class="text-gray-600 mt-1">Altere os dados da manutenção corretiva agendada com o chamado
                {{ $manutencao->chamado }}, para o cliente {{$manutencao->cliente->nome}}.</p>
        </div>
        <a href="{{ route('manutencoes.corretiva.index') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-4 rounded-lg">Voltar para a Lista</a>
    </div>
    <div class="bg-white p-8 rounded-lg shadow-md">
        <form action="{{ route('manutencoes.update', $manutencao->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="tipo" value="{{ $manutencao->tipo }}">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-2">Cliente</label>
                    <select id="cliente_id" name="cliente_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                        <option value="">Selecione um cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ old('cliente_id', $manutencao->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nome }}</option>
                        @endforeach
                    </select>
                    @error('cliente_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="chamado" class="block text-sm font-medium text-gray-700 mb-2">Chamado</label>
                    <input type="text" id="chamado" name="chamado" value="{{ old('chamado', $manutencao->chamado) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('chamado') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="data_inicio_atendimento" class="block text-sm font-medium text-gray-700 mb-2">Data Início Atendimento <span class="text-red-500">*</span></label>
                    <input type="date" id="data_inicio_atendimento" name="data_inicio_atendimento" value="{{ old('data_inicio_atendimento', $manutencao->data_inicio_atendimento) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    @error('data_inicio_atendimento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="data_fim_atendimento" class="block text-sm font-medium text-gray-700 mb-2">Data Fim Atendimento</label>
                    <input type="date" id="data_fim_atendimento" name="data_fim_atendimento" value="{{ old('data_fim_atendimento', $manutencao->data_fim_atendimento) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('data_fim_atendimento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror 
                </div>
                <div>
                    <label for="solicitante" class="block text-sm font-medium text-gray-700 mb-2">Solicitante</label>
                    <input type="text" id="solicitante" name="solicitante" value="{{ old('solicitante', $manutencao->solicitante) }}"   
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('solicitante') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                        <option value="Agendada" {{ old('status', $manutencao->status) == 'Agendada' ? 'selected' : '' }}>Agendada</option>
                        <option value="Em Andamento" {{ old('status', $manutencao->status) == 'Em Andamento' ? 'selected' : '' }}>Em Andamento</option>
                        <option value="Concluída" {{ old('status', $manutencao->status) == 'Concluída' ? 'selected' : '' }}>Concluída</option>
                        <option value="Cancelada" {{ old('status', $manutencao->status) == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="lg:col-span-3">
                    <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                    <textarea id="descricao" name="descricao" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('descricao', $manutencao->descricao) }}</textarea>
                    @error('descricao') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex justify-end mt-10 pt-6">
                <a href="{{ route('manutencoes.corretiva.index') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-6 rounded-lg mr-4">Cancelar</a>
                <button type="submit"
                    class="bg-blue-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Atualizar Manutenção
                </button>
            </div>
        </form>
    </div>


@endsection