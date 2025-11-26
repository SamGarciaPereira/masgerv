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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
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
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                {{-- Botão de Toggle (Expandir) --}}
                <button class="toggle-details-btn text-gray-500 hover:text-gray-800 transition-colors"
                    data-target-id="{{ $conta->id }}">
                    <i class="bi bi-chevron-down toggle-arrow inline-block transition-transform duration-300"></i>
                </button>
            </td>
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
                    {{-- Botão do Clips para abrir Modal --}}
                    <button onclick="openAnexoModal({{ $conta->id }}, '{{ $conta->descricao }}')" 
                            class="text-gray-500 hover:text-blue-600 mr-3" 
                            title="Anexar Arquivo">
                        <i class="bi bi-paperclip text-lg"></i>
                    </button>
                </div>
            </td>
        </tr>
        <tr id="details-{{ $conta->id }}" class="hidden details-row bg-gray-50 border-b border-gray-200">
            <td colspan="8" class="px-6 py-4">
                <div class="flex flex-col gap-2">
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 flex items-center">
                        <i class="bi bi-folder2-open mr-1"></i> Arquivos Anexados
                    </h4>
                    
                    @if($conta->anexos && $conta->anexos->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($conta->anexos as $anexo)
                                <div class="bg-white border border-gray-200 rounded-md p-3 flex items-center justify-between shadow-sm hover:shadow-md transition">
                                    <div class="flex items-center overflow-hidden">
                                        @if(Str::endsWith(strtolower($anexo->nome_original), '.pdf'))
                                            <i class="bi bi-file-earmark-pdf-fill text-red-500 text-xl mr-3 flex-shrink-0"></i>
                                        @else
                                            <i class="bi bi-file-earmark-image-fill text-blue-500 text-xl mr-3 flex-shrink-0"></i>
                                        @endif
                                        
                                        <div class="truncate">
                                            <p class="text-sm font-medium text-gray-700 truncate" title="{{ $anexo->nome_original }}">
                                                {{ $anexo->nome_original }}
                                            </p>
                                            <p class="text-xs text-gray-400">{{ $anexo->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 ml-2">
                                        <a href="{{ route('anexos.show', $anexo->id) }}" target="_blank" 
                                           class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded transition" 
                                           title="Visualizar">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="{{ route('anexos.download', $anexo->id) }}" 
                                           class="p-1.5 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded transition" 
                                           title="Baixar">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <form action="{{ route('anexos.destroy', $anexo->id) }}" method="POST" onsubmit="return confirm('Excluir arquivo?');" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded transition" title="Excluir">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">Nenhum anexo encontrado para esta conta.</p>
                    @endif
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Nenhuma conta a pagar encontrada.</td>
        </tr>
    @endforelse
</tbody>
        </table>
    </div>
</div> 

<div id="anexoModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-opacity-10 backdrop-blur-sm transition-opacity"></div>

    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            
            <form action="{{ route('anexos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="model_id" id="modalModelId">
                <input type="hidden" name="model_type" value="App\Models\ContasPagar">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="bi bi-paperclip text-blue-600 text-xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Anexar Arquivo
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">
                                    Adicionando anexo para: <strong id="modalModelName"></strong>
                                </p>
                                
                                <label class="block text-sm font-medium text-gray-700 mb-2">Selecione o arquivo (PDF ou Imagem)</label>
                                <input type="file" name="arquivo" required accept=".pdf,image/*"
                                       class="block w-full text-sm text-gray-500
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-md file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-blue-50 file:text-blue-700
                                              hover:file:bg-blue-100">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Enviar Anexo
                    </button>
                    <button type="button" onclick="closeAnexoModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/script.js') }}"></script>
@vite('resources/js/app.js')
@endsection    