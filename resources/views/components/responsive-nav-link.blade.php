@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block border-l-4 border-indigo-600 bg-indigo-50 py-2 pe-4 ps-3 text-base font-semibold text-slate-950'
    : 'block border-l-4 border-transparent py-2 pe-4 ps-3 text-base font-semibold text-slate-600 transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
