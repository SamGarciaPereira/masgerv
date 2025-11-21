@extends('layouts.main')

@section('title', 'Magserv | Dashboard')

@section('content')

<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600 mt-1">Visão geral de <strong>{{ ucfirst(\Carbon\Carbon::create()->month($mes)->locale('pt_BR')->translatedFormat('F')) }}</strong></p>
    </div>
    
    <form method="GET" action="{{ route('home') }}" class="flex items-center gap-2 bg-white p-2 rounded-lg shadow-sm">
        <select name="mes" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
            @foreach(range(1, 12) as $m)
                <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>
                    {{ ucfirst(\Carbon\Carbon::create()->month($mes)->locale('pt_BR')->translatedFormat('F')) }}
                </option>
            @endforeach
        </select>
        
        <select name="ano" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
            @foreach(range(now()->year - 2, now()->year + 1) as $y)
                <option value="{{ $y }}" {{ $ano == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition">
            <i class="bi bi-filter"></i> Filtrar
        </button>
    </form>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between border-l-4 border-blue-500">
        <div>
            <p class="text-sm font-medium text-gray-500">Processos Ativos</p>
            <p class="text-3xl font-bold text-gray-800">{{ $processosCount }}</p>
        </div>
        <div class="bg-blue-50 text-blue-600 p-3 rounded-full">
            <i class="bi bi-inboxes-fill text-2xl"></i>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between border-l-4 border-yellow-500">
        <div>
            <p class="text-sm font-medium text-gray-500">Orçamentos Pendentes</p>
            <p class="text-3xl font-bold text-gray-800">{{ $orcamentosCount }}</p>
        </div>
        <div class="bg-yellow-50 text-yellow-600 p-3 rounded-full">
            <i class="bi bi-file-earmark-ruled-fill text-2xl"></i>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between border-l-4 border-green-500">
        <div>
            <p class="text-sm font-medium text-gray-500">Manutenções (Início)</p>
            <p class="text-3xl font-bold text-gray-800">{{ $manutencoesCount }}</p>
        </div>
        <div class="bg-green-50 text-green-600 p-3 rounded-full">
            <i class="bi bi-hammer text-2xl"></i>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between border-l-4 border-red-500">
        <div>
            <p class="text-sm font-medium text-gray-500">A Receber (Pendente)</p>
            <p class="text-2xl font-bold text-gray-800">R$ {{ number_format($totalReceber, 2, ',', '.') }}</p>
        </div>
        <div class="bg-red-50 text-red-600 p-3 rounded-full">
            <i class="bi bi-cash-stack text-2xl"></i>
        </div>
    </div>
</div>

<div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg text-gray-800">Receita Realizada (Dia a Dia)</h3>
            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">Ref: {{ $mes }}/{{ $ano }}</span>
        </div>
        <div class="relative h-72 w-full">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="font-bold text-lg mb-4 text-gray-800">Últimas Atividades do Sistema</h3>
        <ul class="space-y-4 max-h-72 overflow-y-auto custom-scrollbar pr-2">
            @forelse ($atividades as $atividade)
                <li class="flex items-start group">
                    <div class="mt-1">
                        @if (str_contains(strtolower($atividade->description), 'cadastrado') || str_contains(strtolower($atividade->description), 'criado'))
                            <div class="bg-blue-100 text-blue-600 p-1.5 rounded-full mr-3 group-hover:bg-blue-200 transition"><i class="bi bi-plus-lg text-sm"></i></div>
                        @elseif (str_contains(strtolower($atividade->description), 'atualizado') || str_contains(strtolower($atividade->description), 'editado'))
                            <div class="bg-yellow-100 text-yellow-600 p-1.5 rounded-full mr-3 group-hover:bg-yellow-200 transition"><i class="bi bi-pencil-fill text-sm"></i></div>
                        @elseif (str_contains(strtolower($atividade->description), 'removido') || str_contains(strtolower($atividade->description), 'excluído'))
                            <div class="bg-red-100 text-red-600 p-1.5 rounded-full mr-3 group-hover:bg-red-200 transition"><i class="bi bi-trash-fill text-sm"></i></div>
                        @else
                            <div class="bg-gray-100 text-gray-600 p-1.5 rounded-full mr-3 group-hover:bg-gray-200 transition"><i class="bi bi-info-lg text-sm"></i></div>
                        @endif
                    </div>
                    <div>
                        <p class="font-medium text-sm text-gray-700 group-hover:text-blue-600 transition">{{ $atividade->description }}</p>
                        <p class="text-xs text-gray-400">{{ $atividade->created_at->diffForHumans() }}</p>
                    </div>
                </li>
            @empty
                <li class="text-sm text-gray-500 text-center py-4">Nenhuma atividade recente registrada.</li>
            @endforelse
        </ul>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        const labels = @json($labelsGrafico);
        const dataValues = @json($dadosGrafico);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Receita (R$)',
                    data: dataValues,
                    backgroundColor: 'rgba(59, 130, 246, 0.8)', // Tailwind blue-500
                    hoverBackgroundColor: 'rgba(37, 99, 235, 1)', // Tailwind blue-600
                    borderRadius: 4,
                    barPercentage: 0.7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) { label += ': '; }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 4], color: '#f3f4f6' },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL', maximumSignificantDigits: 3});
                            },
                            font: { size: 11 }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    }
                }
            }
        });
    });
</script>

@endsection