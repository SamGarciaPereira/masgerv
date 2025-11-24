@extends('layouts.main')

@section('title', 'Magserv | Contas a Pagar')

@section('content')

<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Contas a Pagar</h1>
        <p class="text-gray-600 mt-1">Gerencie todas as contas a pagar pendentes e efetuadas.</p>
    </div>
    <a href="{{ route('financeiro.contas-pagar.create') }}" class="bg-blue-600 text-white hover:bg-blue-700 font-medium py-2 px-4 rounded-lg flex items-center shadow-sm">
        <i class="bi bi-plus-lg mr-2"></i>
        Nova Conta a Pagar
    </a>
</div>

<div class="bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-200">
    <form method="GET" action="{{ route('financeiro.contas-pagar.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
        
        <div class="md:col-span-5">
            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pesquisar</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-search text-gray-400"></i>
                </div>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Fornecedor, Descrição..."
                       class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div class="md:col-span-3">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                <option value="">Todos</option>
                <option value="Pendente" {{ request('status') == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                <option value="Pago" {{ request('status') == 'Pago' ? 'selected' : '' }}>Pago</option>
                <option value="Atrasado" {{ request('status') == 'Atrasado' ? 'selected' : '' }}>Atrasado</option>
            </select>
        </div>

        <div class="md:col-span-3">
            <label for="ordem" class="block text-sm font-medium text-gray-700 mb-1">Ordenar</label>
            <select name="ordem" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                <option value="recentes" {{ request('ordem') == 'recentes' ? 'selected' : '' }}>Recentes</option>
                <option value="antigos" {{ request('ordem') == 'antigos' ? 'selected' : '' }}>Antigos</option>
                <option value="maior_valor" {{ request('ordem') == 'maior_valor' ? 'selected' : '' }}>Maior Valor</option>
            </select>
        </div>  
        <div class="md:col-span-1">
            <button type="submit" class="bg-blue-600 text-white w-full py-2 rounded-md text-sm hover:bg-blue-700 transition" title="Filtrar">
                <i class="bi bi-filter"></i>
            </button>
        </div>
    </form>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DANFE</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fornecedor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vencimento</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($contasPagar as $conta) 
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $conta->danfe ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $conta->fornecedor ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $conta->descricao }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">R$ {{ number_format($conta->valor, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
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
                                <a href="{{ route('financeiro.contas-pagar.edit', $conta->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                    <i class="bi bi-pencil-fill text-base"></i>
                                </a>
                                <form action="{{ route('financeiro.contas-pagar.destroy', $conta->id) }}" method="POST" onsubmit="return confirm('Tem certeza?');">
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
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Nenhuma conta a pagar encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>  
@endsection    