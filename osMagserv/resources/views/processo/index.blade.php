@extends('layouts.main')

@section('title', 'Gestão de Processos')

@section('content')

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestão de Processos</h1>
            <p class="text-gray-600 mt-1">Visualize e gerencie todos os processos em andamento.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white p-8 rounded-lg shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NF</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proposta
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                   @forelse ($processos as $processo)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">{{ $processo->nf ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $processo->orcamento->numero_proposta ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $processo->orcamento->cliente->nome ?? 'N/A' }} </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">R$ {{ number_format($processo->orcamento->valor_total ?? 0, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClass = [
                                    'Em Aberto' => 'bg-yellow-100 text-yellow-800',
                                    'Finalizado' => 'bg-blue-100 text-blue-800',
                                    'Faturado' => 'bg-green-100 text-green-800',
                                ][$processo->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">{{ $processo->status }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('processos.edit', $processo->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Gerenciar Processo">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Nenhum processo iniciado.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection