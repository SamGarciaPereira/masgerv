@extends('layouts.main')

@section('title', 'Contas a Receber')

@section('content')

<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Contas a Receber</h1>
        <p class="text-gray-600 mt-1">Gerencie todos os recebimentos pendentes e efetuados.</p>
    </div>
    <a href="{{ route('financeiro.contas-receber.create') }}" class="bg-blue-600 text-white hover:bg-blue-700 font-medium py-2 px-4 rounded-lg flex items-center shadow-sm">
        <i class="bi bi-plus-lg mr-2"></i>
        Nova Conta a Receber
    </a>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vencimento</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($contasReceber as $conta) 
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $conta->cliente->nome ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            @if ($conta->processo && $conta->processo->orcamento)
                                Faturamento do orçamento: {{ $conta->processo->orcamento->numero_proposta }} 
                            @else
                                {{ $conta->descricao }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">R$ {{ number_format($conta->valor, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $conta->data_vencimento ? \Carbon\Carbon::parse($conta->data_vencimento)->format('d/m/Y') : 'Não definida' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClass = [
                                    'Pendente' => 'bg-yellow-100 text-yellow-800',
                                    'Pago' => 'bg-green-100 text-green-800',
                                    'Atrasado' => 'bg-red-100 text-red-800',
                                ][$conta->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ $conta->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('financeiro.contas-receber.edit', $conta->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                    <i class="bi bi-pencil-fill text-base"></i>
                                </a>
                                <form action="{{ route('financeiro.contas-receber.destroy', $conta->id) }}" method="POST" onsubmit="return confirm('Tem certeza?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Remover">
                                        <i class="bi bi-trash-fill text-base"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Nenhuma conta a receber encontrada.
                        </td>
                    </tr>
                @endforelse 
            </tbody>
        </table>
    </div>
</div>

@endsection