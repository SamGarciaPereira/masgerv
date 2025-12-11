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

    @if($processo->status === 'Faturado' && $processo->contasReceber->count() > 0)
        @foreach($processo->contasReceber as $conta)
        <div class="bg-white p-8 rounded-lg shadow-md mt-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Faturamento e Pagamentos Parciais</h2>
            
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Valor Total</p>
                        <p class="text-lg font-bold text-gray-900">R$ {{ number_format($conta->valor, 2, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Pago</p>
                        <p class="text-lg font-bold text-green-600">R$ {{ number_format($conta->totalPago(), 2, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Saldo Restante</p>
                        <p class="text-lg font-bold text-red-600">R$ {{ number_format($conta->saldoRestante(), 2, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <x-status-badge :status="$conta->status" />
                    </div>
                </div>
            </div>

            @if($conta->saldoRestante() > 0)
            <div class="mb-6 p-6 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Adicionar Pagamento Parcial</h3>
                <form action="{{ route('pagamentos-parciais.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="contas_receber_id" value="{{ $conta->id }}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="valor" class="block text-sm font-medium text-gray-700 mb-2">Valor do Pagamento <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" id="valor" name="valor" value="{{ old('valor') }}"
                                placeholder="0,00"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                            @error('valor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="data_pagamento" class="block text-sm font-medium text-gray-700 mb-2">Data do Pagamento <span class="text-red-500">*</span></label>
                            <input type="date" id="data_pagamento" name="data_pagamento" value="{{ old('data_pagamento', date('Y-m-d')) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                            @error('data_pagamento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="observacao" class="block text-sm font-medium text-gray-700 mb-2">Observação</label>
                            <input type="text" id="observacao" name="observacao" value="{{ old('observacao') }}"
                                placeholder="Opcional"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('observacao') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="bg-green-600 text-white hover:bg-green-700 font-medium py-2 px-6 rounded-lg">
                            <i class="bi bi-plus-lg mr-2"></i>Registrar Pagamento
                        </button>
                    </div>
                </form>
            </div>
            @endif

            @if($conta->pagamentosParciais->count() > 0)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Histórico de Pagamentos</h3>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Observação</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($conta->pagamentosParciais as $pagamento)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $pagamento->data_pagamento->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                    R$ {{ number_format($pagamento->valor, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $pagamento->observacao ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <form action="{{ route('pagamentos-parciais.destroy', $pagamento->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja remover este pagamento?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Remover">
                                            <i class="bi bi-trash-fill text-base"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <p class="text-gray-500 text-center py-4">Nenhum pagamento parcial registrado ainda.</p>
            @endif
        </div>
        @endforeach
    @endif

@endsection