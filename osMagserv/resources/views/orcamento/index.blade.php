@extends('layouts.main')

@section('content')

<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Lista de Orçamentos</h1>
        <p class="text-gray-600 mt-1">Gerencie todas as propostas enviadas.</p>
    </div>
    <a href="{{ route('orcamentos.create') }}" class="bg-blue-600 text-white hover:bg-blue-700 font-medium py-2 px-4 rounded-lg flex items-center shadow-sm">
        <i class="bi bi-plus-lg mr-2"></i>
        Cadastrar Novo Orçamento
    </a>
</div>

@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
        <p>{{ session('success') }}</p>
    </div>
@endif

<div class="bg-white rounded-lg shadow-md">
    <div class="overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nº Proposta</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($orcamentos as $orcamento)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <button class="toggle-details-btn text-gray-500 hover:text-gray-800" data-target-id="{{ $orcamento->id }}">
                                <i class="bi bi-chevron-down transition-transform duration-300"></i>
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $orcamento->numero_proposta }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $orcamento->cliente->nome ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">R$ {{ number_format($orcamento->valor, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @switch($orcamento->status)
                                    @case('Aprovado') bg-green-100 text-green-800 @break
                                    @case('Enviado') bg-blue-100 text-blue-800 @break
                                    @case('Em Andamento') bg-yellow-100 text-yellow-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch">
                                {{ $orcamento->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('orcamentos.edit', $orcamento->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                    <i class="bi bi-pencil-fill text-base"></i>
                                </a>
                                <form action="{{ route('orcamentos.destroy', $orcamento->id) }}" method="POST" onsubmit="return confirm('Tem certeza?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Remover">
                                        <i class="bi bi-trash-fill text-base"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr id="details-{{ $orcamento->id }}" class="hidden details-row">
                        <td colspan="6" class="px-6 py-4 bg-gray-50">
                            <div class="p-4 text-sm text-gray-700 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <p><strong>Escopo:</strong><br>{{ $orcamento->escopo }}</p>
                                <div>
                                    <p><strong>Data de Envio:</strong> {{ $orcamento->data_envio ? \Carbon\Carbon::parse($orcamento->data_envio)->format('d/m/Y') : 'Não definida' }}</p>
                                    <p><strong>Data Limite p/ Resposta:</strong> {{ $orcamento->data_limite_envio ? \Carbon\Carbon::parse($orcamento->data_limite_envio)->format('d/m/Y') : 'Não definida' }}</p>
                                    <p><strong>Data de Aprovação:</strong> {{ $orcamento->data_aprovacao ? \Carbon\Carbon::parse($orcamento->data_aprovacao)->format('d/m/Y') : 'Não definida' }}</p>
                                    <p><strong>Revisão:</strong> {{ $orcamento->revisao }}</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Nenhum orçamento cadastrado ainda.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
