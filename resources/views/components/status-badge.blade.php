@props(['status'])

@php
    $colors = [
        'Pendente'      => 'bg-yellow-100 text-yellow-800 border-yellow-200',
        'Agendada'      => 'bg-orange-100 text-orange-800 border-orange-200',
        'Em Andamento'  => 'bg-blue-100 text-blue-800 border-blue-200',
        'Concluída'     => 'bg-green-100 text-green-800 border-green-200',
        'Cancelada'     => 'bg-red-100 text-red-800 border-red-200',
        'Aprovado'      => 'bg-green-100 text-green-800 border-green-200',
        'Recusado'      => 'bg-red-100 text-red-800 border-red-200',
        'Pago'          => 'bg-green-100 text-green-800 border-green-200',
        'Atrasado'       => 'bg-red-100 text-red-800 border-red-200',
        '1'             => 'bg-green-100 text-green-800 border-green-200', 
        '0'             => 'bg-red-100 text-red-800 border-red-200',  
    ];

    $colorClass = $colors[$status] ?? 'bg-gray-100 text-gray-800 border-gray-200';

    $displayText = $status;
    if ($status === 1 || $status === '1') $displayText = 'Ativo';
    if ($status === 0 || $status === '0') $displayText = 'Inativo';
@endphp

<span {{ $attributes->merge(['class' => "px-2 inline-flex text-xs leading-5 font-semibold rounded-full border $colorClass"]) }}>
    {{ $displayText }}
</span>