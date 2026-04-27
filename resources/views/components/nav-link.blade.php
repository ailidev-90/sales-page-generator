@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center border-b-2 border-indigo-600 px-1 pt-1 text-sm font-semibold text-slate-950'
    : 'inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-semibold text-slate-500 transition hover:border-slate-300 hover:text-slate-800';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
