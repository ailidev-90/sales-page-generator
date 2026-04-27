@php
    $template = $salesPage->template ?: 'modern';
    $themes = [
        'modern' => [
            'page' => 'bg-slate-50 text-slate-950',
            'hero' => 'bg-white',
            'accent' => 'bg-indigo-600 text-white hover:bg-indigo-500',
            'secondary' => 'border-slate-300 text-slate-800 hover:bg-slate-50',
            'muted' => 'text-slate-600',
            'card' => 'border-slate-200 bg-white',
            'band' => 'bg-slate-950 text-white',
            'chip' => 'bg-indigo-50 text-indigo-700',
        ],
        'elegant' => [
            'page' => 'bg-stone-50 text-stone-950',
            'hero' => 'bg-[#faf7f2]',
            'accent' => 'bg-stone-900 text-white hover:bg-stone-700',
            'secondary' => 'border-stone-300 text-stone-800 hover:bg-white',
            'muted' => 'text-stone-600',
            'card' => 'border-stone-200 bg-white',
            'band' => 'bg-[#2f2a24] text-white',
            'chip' => 'bg-[#efe7db] text-stone-800',
        ],
        'bold' => [
            'page' => 'bg-zinc-950 text-white',
            'hero' => 'bg-zinc-950',
            'accent' => 'bg-lime-300 text-zinc-950 hover:bg-lime-200',
            'secondary' => 'border-zinc-700 text-white hover:bg-zinc-900',
            'muted' => 'text-zinc-300',
            'card' => 'border-zinc-700 bg-zinc-900',
            'band' => 'bg-lime-300 text-zinc-950',
            'chip' => 'bg-fuchsia-500 text-white',
        ],
    ];
    $theme = $themes[$template] ?? $themes['modern'];
    $benefits = $content['benefits'] ?? [];
    $features = $content['features'] ?? [];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="eyebrow">Live preview</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">{{ $salesPage->product_name }}</h1>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    {{ ucfirst($template) }} template with {{ $salesPage->tone ?: 'professional' }} tone.
                </p>
            </div>
            <div class="grid gap-2 sm:flex sm:flex-wrap">
                <a href="{{ route('sales-pages.edit', $salesPage) }}" class="btn-secondary">Edit and Regenerate</a>
                <a href="{{ route('sales-pages.export-html', $salesPage) }}" class="btn-primary">Export HTML</a>
            </div>
        </div>
    </x-slot>

    <div class="bg-slate-100 px-0 py-0 sm:px-4 sm:py-8 lg:px-8">
        <div class="mx-auto max-w-7xl overflow-hidden bg-white shadow-sm ring-1 ring-slate-200 sm:rounded-lg">
            <div class="{{ $theme['page'] }}">
                <section class="{{ $theme['hero'] }}">
                    <div class="mx-auto grid max-w-7xl gap-10 px-5 py-14 sm:px-8 lg:grid-cols-[1.1fr_0.9fr] lg:px-10 lg:py-20">
                        <div class="flex flex-col justify-center">
                            <span class="inline-flex w-fit rounded-full px-3 py-1 text-sm font-bold {{ $theme['chip'] }}">
                                {{ ucfirst($salesPage->tone ?: 'professional') }} offer
                            </span>
                            <h2 class="mt-6 max-w-4xl text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                                {{ $content['headline'] ?? $salesPage->product_name }}
                            </h2>
                            <p class="mt-6 max-w-2xl text-lg leading-8 {{ $theme['muted'] }}">
                                {{ $content['subheadline'] ?? '' }}
                            </p>
                            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                                <a href="#pricing" class="inline-flex items-center justify-center rounded-lg px-5 py-3 text-base font-bold transition {{ $theme['accent'] }}">
                                    {{ $content['cta_text'] ?? 'Get started' }}
                                </a>
                                <a href="{{ route('sales-pages.index') }}" class="inline-flex items-center justify-center rounded-lg border px-5 py-3 text-base font-bold transition {{ $theme['secondary'] }}">
                                    Saved pages
                                </a>
                            </div>
                        </div>

                        <div class="rounded-lg border p-6 shadow-sm {{ $theme['card'] }}">
                            <p class="text-sm font-bold uppercase tracking-wide {{ $theme['muted'] }}">Offer snapshot</p>
                            <dl class="mt-6 space-y-5">
                                <div>
                                    <dt class="text-sm font-semibold {{ $theme['muted'] }}">Audience</dt>
                                    <dd class="mt-1 text-lg font-bold">{{ $salesPage->target_audience }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-semibold {{ $theme['muted'] }}">Pricing</dt>
                                    <dd class="mt-1 text-3xl font-extrabold">{{ $content['pricing_display'] ?? ($salesPage->price ?: 'Contact us') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-semibold {{ $theme['muted'] }}">Proof</dt>
                                    <dd class="mt-1 leading-7 {{ $theme['muted'] }}">{{ $content['social_proof_placeholder'] ?? '' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </section>

                <section class="mx-auto max-w-7xl px-5 py-14 sm:px-8 lg:px-10">
                    <div class="grid gap-8 lg:grid-cols-[0.85fr_1.15fr]">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-wide {{ $theme['muted'] }}">Product description</p>
                            <h3 class="mt-3 text-3xl font-extrabold">Built for {{ $salesPage->target_audience }}</h3>
                        </div>
                        <p class="text-lg leading-8 {{ $theme['muted'] }}">
                            {{ $content['product_description'] ?? $salesPage->description }}
                        </p>
                    </div>
                </section>

                <section class="mx-auto max-w-7xl px-5 pb-14 sm:px-8 lg:px-10">
                    <div class="mb-6">
                        <p class="text-sm font-bold uppercase tracking-wide {{ $theme['muted'] }}">Benefits</p>
                        <h3 class="mt-2 text-3xl font-extrabold">Why customers choose it</h3>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                        @forelse ($benefits as $benefit)
                            <div class="rounded-lg border p-5 shadow-sm {{ $theme['card'] }}">
                                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg {{ $theme['chip'] }}">
                                    <span class="text-sm font-extrabold">{{ $loop->iteration }}</span>
                                </div>
                                <p class="leading-7 {{ $theme['muted'] }}">{{ $benefit }}</p>
                            </div>
                        @empty
                            <div class="rounded-lg border p-5 {{ $theme['card'] }}">
                                <p class="{{ $theme['muted'] }}">Benefits will appear here after regeneration.</p>
                            </div>
                        @endforelse
                    </div>
                </section>

                <section class="{{ $theme['band'] }}">
                    <div class="mx-auto max-w-7xl px-5 py-14 sm:px-8 lg:px-10">
                        <div class="grid gap-8 lg:grid-cols-[0.7fr_1.3fr]">
                            <div>
                                <p class="text-sm font-bold uppercase tracking-wide opacity-75">Features</p>
                                <h3 class="mt-3 text-3xl font-extrabold">Everything the page needs to sell clearly</h3>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2">
                                @forelse ($features as $feature)
                                    <div class="rounded-lg border border-white/15 bg-white/10 p-4">
                                        <p class="font-semibold">{{ $feature }}</p>
                                    </div>
                                @empty
                                    <div class="rounded-lg border border-white/15 bg-white/10 p-4">
                                        <p class="font-semibold">Features will appear here after regeneration.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </section>

                <section id="pricing" class="mx-auto max-w-4xl px-5 py-16 text-center sm:px-8 lg:px-10">
                    <p class="text-sm font-bold uppercase tracking-wide {{ $theme['muted'] }}">Ready when you are</p>
                    <h3 class="mt-3 text-4xl font-extrabold">{{ $content['pricing_display'] ?? ($salesPage->price ?: 'Contact us') }}</h3>
                    <p class="mx-auto mt-5 max-w-2xl text-lg leading-8 {{ $theme['muted'] }}">
                        {{ $content['social_proof_placeholder'] ?? '' }}
                    </p>
                    <a href="#" class="mt-8 inline-flex items-center justify-center rounded-lg px-6 py-3 text-base font-bold transition {{ $theme['accent'] }}">
                        {{ $content['cta_text'] ?? 'Get started' }}
                    </a>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
