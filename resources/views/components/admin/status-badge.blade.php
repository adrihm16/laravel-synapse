@props(['status'])

@php
    $statusClasses = [
        'pendiente' => 'bg-orange-100 text-orange-800',
        'pagado'    => 'bg-blue-100 text-blue-800',
        'enviado'   => 'bg-purple-100 text-purple-800',
        'entregado' => 'bg-green-100 text-green-800',
        'cancelado' => 'bg-red-100 text-red-800',
    ];
    $class = $statusClasses[strtolower($status)] ?? 'bg-gray-100 text-gray-800';
@endphp

<span {{ $attributes->merge(['class' => "px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider $class"]) }}>
    {{ $status }}
</span>
