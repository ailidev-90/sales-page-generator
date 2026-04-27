@php
    $template = $salesPage->template ?: 'modern';
    $styles = [
        'modern' => [
            'body' => 'bg-slate-50 text-slate-950',
            'hero' => 'bg-white',
            'accent' => 'bg-indigo-600 text-white',
            'muted' => 'text-slate-600',
            'card' => 'border-slate-200 bg-white',
            'band' => 'bg-slate-950 text-white',
            'chip' => 'bg-indigo-50 text-indigo-700',
        ],
        'elegant' => [
            'body' => 'bg-stone-50 text-stone-950',
            'hero' => 'bg-[#faf7f2]',
            'accent' => 'bg-stone-900 text-white',
            'muted' => 'text-stone-600',
            'card' => 'border-stone-200 bg-white',
            'band' => 'bg-[#2f2a24] text-white',
            'chip' => 'bg-[#efe7db] text-stone-800',
        ],
        'bold' => [
            'body' => 'bg-zinc-950 text-white',
            'hero' => 'bg-zinc-950',
            'accent' => 'bg-lime-300 text-zinc-950',
            'muted' => 'text-zinc-300',
            'card' => 'border-zinc-700 bg-zinc-900',
            'band' => 'bg-lime-300 text-zinc-950',
            'chip' => 'bg-fuchsia-500 text-white',
        ],
    ];
    $theme = $styles[$template] ?? $styles['modern'];
    $benefits = $content['benefits'] ?? [];
    $features = $content['features'] ?? [];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $salesPage->product_name }} Sales Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="{{ $theme['body'] }} font-sans">
    <main>
        <section class="{{ $theme['hero'] }}">
            <div class="mx-auto grid max-w-7xl gap-10 px-6 py-16 lg:grid-cols-[1.15fr_0.85fr] lg:py-24">
                <div>
                    <span class="inline-flex rounded-full px-3 py-1 text-sm font-bold {{ $theme['chip'] }}">
                        {{ ucfirst($salesPage->tone ?: 'professional') }} offer
                    </span>
                    <h1 class="mt-6 max-w-4xl text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                        {{ $content['headline'] ?? $salesPage->product_name }}
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg leading-8 {{ $theme['muted'] }}">
                        {{ $content['subheadline'] ?? '' }}
                    </p>
                    <a href="#pricing" class="mt-8 inline-flex items-center justify-center rounded-lg px-6 py-3 text-base font-bold {{ $theme['accent'] }}">
                        {{ $content['cta_text'] ?? 'Get started' }}
                    </a>
                </div>

                <div class="rounded-lg border p-6 shadow-sm {{ $theme['card'] }}">
                    <p class="text-sm font-bold uppercase tracking-wide {{ $theme['muted'] }}">Pricing</p>
                    <p class="mt-4 text-4xl font-extrabold">{{ $content['pricing_display'] ?? ($salesPage->price ?: 'Contact us') }}</p>
                    <p class="mt-5 leading-7 {{ $theme['muted'] }}">
                        {{ $content['social_proof_placeholder'] ?? '' }}
                    </p>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-6 py-14">
            <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">
                <div>
                    <p class="text-sm font-bold uppercase tracking-wide {{ $theme['muted'] }}">Product description</p>
                    <h2 class="mt-3 text-3xl font-extrabold">Built for {{ $salesPage->target_audience }}</h2>
                </div>
                <p class="text-lg leading-8 {{ $theme['muted'] }}">
                    {{ $content['product_description'] ?? $salesPage->description }}
                </p>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-6 pb-14">
            <p class="text-sm font-bold uppercase tracking-wide {{ $theme['muted'] }}">Benefits</p>
            <h2 class="mt-2 text-3xl font-extrabold">Why customers choose it</h2>
            <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($benefits as $benefit)
                    <div class="rounded-lg border p-5 shadow-sm {{ $theme['card'] }}">
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg {{ $theme['chip'] }}">
                            <span class="text-sm font-extrabold">{{ $loop->iteration }}</span>
                        </div>
                        <p class="leading-7 {{ $theme['muted'] }}">{{ $benefit }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="{{ $theme['band'] }}">
            <div class="mx-auto max-w-7xl px-6 py-14">
                <div class="grid gap-8 lg:grid-cols-[0.7fr_1.3fr]">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-wide opacity-75">Features</p>
                        <h2 class="mt-3 text-3xl font-extrabold">Everything the page needs to sell clearly</h2>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach ($features as $feature)
                            <div class="rounded-lg border border-white/15 bg-white/10 p-4">
                                <p class="font-semibold">{{ $feature }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section id="pricing" class="mx-auto max-w-4xl px-6 py-16 text-center">
            <p class="text-sm font-bold uppercase tracking-wide {{ $theme['muted'] }}">Ready when you are</p>
            <h2 class="mt-3 text-4xl font-extrabold">{{ $content['pricing_display'] ?? ($salesPage->price ?: 'Contact us') }}</h2>
            <p class="mx-auto mt-5 max-w-2xl text-lg leading-8 {{ $theme['muted'] }}">
                {{ $content['social_proof_placeholder'] ?? '' }}
            </p>
            <a href="#" class="mt-8 inline-flex items-center justify-center rounded-lg px-6 py-3 text-base font-bold {{ $theme['accent'] }}">
                {{ $content['cta_text'] ?? 'Get started' }}
            </a>
        </section>
    </main>
</body>
</html>
