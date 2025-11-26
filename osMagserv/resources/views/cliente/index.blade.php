@extends('layouts.main')

@section('title', 'Magserv | Clientes')

@section('content')

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Lista de Clientes</h1>
            <p class="text-gray-600 mt-1">Gerencie todos os clientes cadastrados no sistema.</p>
        </div>
        <a href="{{ route('clientes.create') }}"
            class="bg-blue-600 text-white hover:bg-blue-700 font-medium py-2 px-4 rounded-lg flex items-center shadow-sm">
            <i class="bi bi-plus-lg mr-2"></i>
            Cadastrar Novo Cliente
        </a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-200">
        <form method="GET" action="{{ route('clientes.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">

            <div class="md:col-span-7">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pesquisar</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="bi bi-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Nome, Razão Social, CNPJ, Responsável ou E-mail..."
                        class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="md:col-span-4">
                <label for="ordem" class="block text-sm font-medium text-gray-700 mb-1">Ordenar</label>
                <select name="ordem" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <option value="recentes" {{ request('ordem') == 'recentes' ? 'selected' : '' }}>Recentes</option>
                    <option value="antigos" {{ request('ordem') == 'antigos' ? 'selected' : '' }}>Antigos</option>
                </select>
            </div>

            <div class="md:col-span-1">
                <button type="submit"
                    class="bg-blue-600 text-white w-full py-2 rounded-md text-sm hover:bg-blue-700 transition"
                    title="Filtrar">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome /
                            Razão Social</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CNPJ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Responsável</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefone
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($clientes as $cliente)
                        <tr>
                            <td class="px-6 py-4">
                                <button class="toggle-details-btn text-gray-500 hover:text-gray-800"
                                    data-target-id="{{ $cliente->id }}">
                                    <i
                                        class="bi bi-chevron-down toggle-arrow inline-block transition-transform duration-300"></i>
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $cliente->nome }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $cliente->documento }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $cliente->responsavel }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $cliente->telefone ?? 'Não informado' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-4">
                                    <!-- Botão Editar -->
                                    <a href="{{ route('clientes.edit', $cliente->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                        <i class="bi bi-pencil-fill text-base"></i>
                                    </a>
                                    <!-- Formulário para Remover -->
                                    <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST"
                                        onsubmit="return confirm('Tem certeza que deseja remover este cliente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Remover">
                                            <i class="bi bi-trash-fill text-base"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr id="details-{{ $cliente->id }}" class="hidden details-row">
                            <td colspan="6" class="px-6 py-2 bg-gray-50">
                                <div class="p-2 text-sm text-gray-700 grid grid-cols-1 md:grid-cols-2 gap-2 max-h-40 overflow-auto">
                                    <div class="space-y-1">
                                        <p class="mb-0"><strong>Endereço:</strong> {{ $cliente->logradouro }},
                                            {{ $cliente->numero }}, {{ $cliente->bairro }}, {{ $cliente->cidade }},
                                            {{ $cliente->estado }}</p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="mb-0"><strong>E-mail:</strong> {{ $cliente->email }}</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                Nenhum cliente cadastrado ainda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection