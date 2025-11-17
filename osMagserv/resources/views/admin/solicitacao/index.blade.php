@extends('layouts.main')

@section('title', 'Solicitações Pendentes')

@section('content')

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Solicitações Pendentes</h1>
    <p class="text-gray-600 mt-1">Gerencie solicitações de manutenção e orçamentos enviadas via WhatsApp.</p>
</div>

@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
        <p>{{ session('success') }}</p>
    </div>
@endif
@if (session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
        <p>{{ session('error') }}</p>
    </div>
@endif
@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
        <strong>Ops!</strong> Havia algo errado:
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-6">
    @forelse ($solicitacoes as $solicitacao)
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4">
            <div class="flex justify-between items-start">
                <div>
                    @if($solicitacao->tipo == 'manutencao_corretiva')
                        <h2 class="text-xl font-bold text-gray-800">Nova Manutenção Corretiva</h2>
                    @elseif($solicitacao->tipo == 'orcamento')
                        <h2 class="text-xl font-bold text-gray-800">Solicitação de Orçamento</h2>
                    @else
                        <h2 class="text-xl font-bold text-gray-800">Nova Solicitação</h2>
                    @endif
                    <p class="text-sm text-gray-500">Recebido em: {{ $solicitacao->created_at->format('d/m/Y \à\s H:i') }}</p>
                </div>
            </div>

            <div class="mt-4 space-y-2 text-gray-700">
                <p><strong>Cliente:</strong> {{ $solicitacao->dados['cliente_nome'] ?? 'Não informado' }}</p>

                @if($solicitacao->tipo == 'manutencao_corretiva')
                    <p><strong>Solicitante:</strong> {{ $solicitacao->dados['solicitante'] ?? 'N/A' }}</p>
                    <p><strong>Descrição:</strong> {{ $solicitacao->dados['descricao'] ?? 'N/A' }}</p>
                    <p><strong>Nº Chamado Cliente:</strong> {{ $solicitacao->dados['chamado'] ?? 'N/A' }}</p>
                
                @elseif($solicitacao->tipo == 'orcamento')
                    <p><strong>Escopo Solicitado:</strong> {{ $solicitacao->dados['escopo'] ?? 'N/A' }}</p>
                @endif
            </div>

            <div class="flex items-center space-x-4 mt-6">
                <form action="{{ route('admin.solicitacoes.approve', $solicitacao->id) }}" method="POST"
                      onsubmit="return confirm('Tem certeza que deseja APROVAR esta solicitação?');">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white hover:bg-green-700 font-medium py-2 px-4 rounded-lg flex items-center shadow-sm">
                        <i class="bi bi-check-lg mr-2"></i> Aprovar
                    </button>
                </form>

                <form action="{{ route('admin.solicitacoes.reject', $solicitacao->id) }}" method="POST"
                  onsubmit="return confirm('Tem certeza que deseja RECUSAR esta solicitação?');">
                @csrf
                <button type="submit" class="bg-red-600 text-white hover:bg-red-700 font-medium py-2 px-4 rounded-lg flex items-center 
                shadow-sm">
                    <i class="bi bi-x-lg mr-2"></i> Recusar
                </button>
            </form>

               
            </div>

        </div>
    @empty
        <div class="bg-white p-8 rounded-lg shadow-md text-center">
            <h3 class="text-lg font-medium text-gray-900">Tudo em dia!</h3>
            <p class="mt-1 text-sm text-gray-500">
                Não há nenhuma solicitação pendente no momento.
            </p>
        </div>
    @endforelse
</div>

@endsection