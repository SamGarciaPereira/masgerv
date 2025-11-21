@extends('layouts.main')

@section('title', 'Magserv | Dashboard')

@section('content')

<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600 mt-1">
            Bem-vindo, <strong>{{ auth()->user()->name ?? 'Usuário' }}</strong>! 
            Dados de {{ ucfirst(\Carbon\Carbon::create()->month($mes)->locale('pt_BR')->translatedFormat('F')) }}/{{ $ano }}.
        </p>
    </div>
    
    <form method="GET" action="{{ route('home') }}" class="flex items-center gap-2 bg-white p-2 rounded-lg shadow-sm">
        <select name="mes" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
            @foreach(range(1, 12) as $m)
                <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>
                    {{ ucfirst(\Carbon\Carbon::create()->month($m)->locale('pt_BR')->translatedFormat('F')) }}
                </option>
            @endforeach
        </select>
        <select name="ano" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
            @foreach(range(now()->year - 4, now()->year + 4) as $y)
                <option value="{{ $y }}" {{ $ano == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition">
            <i class="bi bi-filter"></i>
        </button>
    </form>
</div>

@php
    function renderStatusList($stats, $isMoney = false) {
        if(empty($stats)) return '<p class="text-xs text-gray-400 mt-2">Sem dados no período.</p>';
        
        $html = '<div class="mt-3 space-y-1">';
        foreach($stats as $status => $val) {
            $displayVal = $isMoney ? 'R$ ' . number_format($val, 2, ',', '.') : $val;
            $color = match($status) {
                'Concluída', 'Aprovado', 'Aprovada', 'Finalizado', 'Pago' => 'bg-green-500',
                'Pendente', 'Em Aberto', 'Agendada' => 'bg-yellow-500',
                'Cancelada', 'Recusado', 'Recusada', 'Atrasado' => 'bg-red-500',
                'Em Andamento', 'Enviado' => 'bg-blue-500',
                'Faturado' => 'bg-purple-500',
                default => 'bg-gray-400'
            };

            $html .= '
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center">
                        <span class="w-2 h-2 rounded-full '.$color.' mr-2"></span>
                        <span class="text-gray-600">'.$status.'</span>
                    </div>
                    <span class="font-semibold text-gray-800">'.$displayVal.'</span>
                </div>
            ';
        }
        $html .= '</div>';
        return $html;
    }
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    <div class="bg-white p-4 rounded-lg shadow-md border-t-4 border-gray-600 hover:shadow-lg transition">
        <div class="flex justify-between items-start mb-2">
            <div>
                <p class="text-md font-bold text-gray-500  tracking-wide">Processos</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ array_sum($processosStats) }}</h3>
            </div>
            <div class="bg-gray-100 text-gray-600 p-2 rounded-lg">
                <i class="bi bi-inboxes-fill text-xl"></i>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-2">
            {!! renderStatusList($processosStats) !!}
        </div>
    </div>
    
    <div class="bg-white p-4 rounded-lg shadow-md border-t-4 border-yellow-500 hover:shadow-lg transition">
        <div class="flex justify-between items-start mb-2">
            <div>
                <p class="text-md font-bold text-gray-500  tracking-wide">Orçamentos</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ array_sum($orcamentosStats) }}</h3>
            </div>
            <div class="bg-yellow-50 text-yellow-600 p-2 rounded-lg">
                <i class="bi bi-file-earmark-text-fill text-xl"></i>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-2">
            {!! renderStatusList($orcamentosStats) !!}
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg shadow-md border-t-4 border-blue-500 hover:shadow-lg transition">
        <div class="flex justify-between items-start mb-2">
            <div>
                <p class="text-md font-bold text-gray-500  tracking-wide">Manut. Preventiva</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ array_sum($prevStats) }}</h3>
            </div>
            <div class="bg-blue-50 text-blue-600 p-2 rounded-lg">
                <i class="bi bi-shield-check text-xl"></i>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-2">
            {!! renderStatusList($prevStats) !!}
        </div>
    </div>
    
    <div class="bg-white p-4 rounded-lg shadow-md border-t-4 border-orange-500 hover:shadow-lg transition">
        <div class="flex justify-between items-start mb-2">
            <div>
                <p class="text-md font-bold text-gray-500 tracking-wide">Manut. Corretiva</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ array_sum($corrStats) }}</h3>
            </div>
            <div class="bg-orange-50 text-orange-600 p-2 rounded-lg">
                <i class="bi bi-tools text-xl"></i>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-2">
            {!! renderStatusList($corrStats) !!}
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg shadow-md border-t-4 border-purple-500 hover:shadow-lg transition">
        <div class="flex justify-between items-start mb-2">
            <div>
                <p class="text-md font-bold text-gray-500  tracking-wide">Solicitações</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ array_sum($solicitacoesStats) }}</h3>
            </div>
            <div class="bg-purple-50 text-purple-600 p-2 rounded-lg">
                <i class="bi bi-chat-dots-fill text-xl"></i>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-2">
            {!! renderStatusList($solicitacoesStats) !!}
        </div>
    </div>
    
    
    @if(auth()->user()->isAdmin())
    <div class="bg-white p-4 rounded-lg shadow-md border-t-4 border-green-500 hover:shadow-lg transition">
        <div class="flex justify-between items-start mb-2">
            <div>
                <p class="text-md font-bold text-gray-500  tracking-wide">Receitas</p>
                <h3 class="text-2xl font-bold text-gray-800">R$ {{ number_format($receberStats['Pago'] ?? 0, 2, ',', '.') }}</h3>
            </div>
            <div class="bg-green-50 text-green-600 p-2 rounded-lg">
                <i class="bi bi-arrow-down-circle-fill text-xl"></i>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-2">
            {!! renderStatusList($receberStats, true) !!}
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg shadow-md border-t-4 border-red-500 hover:shadow-lg transition">
        <div class="flex justify-between items-start mb-2">
            <div>
                <p class="text-md font-bold text-gray-500  tracking-wide">Despesas</p>
                <h3 class="text-2xl font-bold text-gray-800">R$ {{ number_format($pagarStats['Pago'] ?? 0, 2, ',', '.') }}</h3>
            </div>
            <div class="bg-red-50 text-red-600 p-2 rounded-lg">
                <i class="bi bi-arrow-up-circle-fill text-xl"></i>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-2">
            {!! renderStatusList($pagarStats, true) !!}
        </div>
    </div>
    @endif
</div>

<div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
    @if(auth()->user()->isAdmin())
    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
            <div>
                <h3 class="font-bold text-lg text-gray-800" id="chartTitle">Fluxo Financeiro (Receitas)</h3>
                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">Ref: {{ $mes }}/{{ $ano }}</span>
            </div>
            
            <div class="flex bg-gray-100 p-1 rounded-lg">
                <button onclick="updateChart('receita')" id="btnReceita" 
                        class="px-4 py-1.5 text-sm font-medium rounded-md shadow-sm bg-white text-blue-600 transition-all duration-200">
                    Receitas
                </button>
                <button onclick="updateChart('despesa')" id="btnDespesa" 
                        class="px-4 py-1.5 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700 transition-all duration-200">
                    Despesas
                </button>
            </div>
        </div>
        
        <div class="relative h-72 w-full">
            <canvas id="financeChart"></canvas>
        </div>
    </div>
    @endif
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="font-bold text-lg mb-4 text-gray-800">Atividades Recentes</h3>
        <ul class="space-y-4 max-h-72 overflow-y-auto custom-scrollbar pr-2">
             @forelse ($atividades as $atividade)
                <li class="flex items-start">
                    <div class="mt-1 bg-gray-100 text-gray-600 p-1.5 rounded-full mr-3">
                        <i class="bi bi-info-lg text-sm"></i>
                    </div>
                    <div>
                        <p class="font-medium text-sm text-gray-700">{{ $atividade->description }}</p>
                        <p class="text-xs text-gray-400">{{ $atividade->created_at->diffForHumans() }}</p>
                    </div>
                </li>
             @empty
                <li class="text-sm text-gray-500">Nenhuma atividade.</li>
             @endforelse
        </ul>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let financeChart = null;

    const chartLabels = @json($labelsGrafico);
    const dataReceita = @json($dadosReceita);
    const dataDespesa = @json($dadosDespesa);
    const configReceita = {
        label: 'Recebido (R$)',
        color: 'rgba(34, 197, 94, 0.7)',      
        hoverColor: 'rgba(22, 163, 74, 1)',   
        borderColor: 'rgba(22, 163, 74, 0.8)' 
    };

    const configDespesa = {
        label: 'Pago (R$)',
        color: 'rgba(239, 68, 68, 0.7)',     
        hoverColor: 'rgba(220, 38, 38, 1)',   
        borderColor: 'rgba(220, 38, 38, 0.8)' 
    };

    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('financeChart').getContext('2d');

        financeChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: configReceita.label,
                    data: dataReceita,
                    backgroundColor: configReceita.color,
                    hoverBackgroundColor: configReceita.hoverColor,
                    borderColor: configReceita.borderColor,
                    borderWidth: 1,
                    borderRadius: 4,
                    barPercentage: 0.6,
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

    function updateChart(type) {
        const btnReceita = document.getElementById('btnReceita');
        const btnDespesa = document.getElementById('btnDespesa');
        const title = document.getElementById('chartTitle');

        if (type === 'receita') {
            financeChart.data.datasets[0].data = dataReceita;
            financeChart.data.datasets[0].label = configReceita.label;
            financeChart.data.datasets[0].backgroundColor = configReceita.color;
            financeChart.data.datasets[0].hoverBackgroundColor = configReceita.hoverColor;
            financeChart.data.datasets[0].borderColor = configReceita.borderColor;
            
            btnReceita.className = "px-4 py-1.5 text-sm font-medium rounded-md shadow-sm bg-white text-blue-600 transition-all duration-200";
            btnDespesa.className = "px-4 py-1.5 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700 transition-all duration-200";
            title.innerText = "Fluxo Financeiro (Receitas)";
        } else {
            financeChart.data.datasets[0].data = dataDespesa;
            financeChart.data.datasets[0].label = configDespesa.label;
            financeChart.data.datasets[0].backgroundColor = configDespesa.color;
            financeChart.data.datasets[0].hoverBackgroundColor = configDespesa.hoverColor;
            financeChart.data.datasets[0].borderColor = configDespesa.borderColor;

            btnDespesa.className = "px-4 py-1.5 text-sm font-medium rounded-md shadow-sm bg-white text-red-600 transition-all duration-200";
            btnReceita.className = "px-4 py-1.5 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700 transition-all duration-200";
            title.innerText = "Fluxo Financeiro (Despesas)";
        }

        financeChart.update();
    }
</script>

@endsection