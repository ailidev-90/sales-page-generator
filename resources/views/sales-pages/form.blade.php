<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="eyebrow">{{ $mode === 'edit' ? 'Regenerate' : 'Generator' }}</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">
                    {{ $mode === 'edit' ? 'Edit sales page inputs' : 'Generate a sales page' }}
                </h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Provide the offer details once, then save a structured landing page you can preview and export.
                </p>
            </div>
            <a href="{{ route('sales-pages.index') }}" class="btn-secondary w-full sm:w-auto">Saved Pages</a>
        </div>
    </x-slot>

    <div class="mx-auto grid max-w-7xl gap-6 px-4 py-8 sm:px-6 lg:grid-cols-[minmax(0,1fr)_360px] lg:px-8">
        <form
            method="POST"
            action="{{ $mode === 'edit' ? route('sales-pages.update', $salesPage) : route('sales-pages.store') }}"
            class="panel overflow-hidden"
            x-data="{ generating: false }"
            x-on:submit="generating = true"
        >
            @csrf
            @if ($mode === 'edit')
                @method('PATCH')
            @endif

            @if ($errors->any())
                <div class="border-b border-rose-200 bg-rose-50 px-5 py-4 sm:px-6">
                    <p class="text-sm font-bold text-rose-800">Please fix the highlighted fields.</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-rose-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-8 p-5 sm:p-6">
                <section>
                    <div class="border-b border-slate-200 pb-4">
                        <h2 class="text-base font-bold text-slate-950">Offer details</h2>
                        <p class="mt-1 text-sm text-slate-500">Core information used to shape the page narrative.</p>
                    </div>

                    <div class="mt-5 grid gap-5 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <x-input-label for="product_name" value="Product or service name" />
                            <x-text-input id="product_name" name="product_name" type="text" :value="old('product_name', $salesPage->product_name)" required autofocus />
                            <x-input-error :messages="$errors->get('product_name')" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="description" value="Description" />
                            <textarea id="description" name="description" rows="5" required class="form-field">{{ old('description', $salesPage->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="target_audience" value="Target audience" />
                            <x-text-input id="target_audience" name="target_audience" type="text" :value="old('target_audience', $salesPage->target_audience)" required />
                            <x-input-error :messages="$errors->get('target_audience')" />
                        </div>

                        <div>
                            <x-input-label for="price" value="Price" />
                            <x-text-input id="price" name="price" type="text" :value="old('price', $salesPage->price)" placeholder="$49/month" />
                            <x-input-error :messages="$errors->get('price')" />
                        </div>
                    </div>
                </section>

                <section>
                    <div class="border-b border-slate-200 pb-4">
                        <h2 class="text-base font-bold text-slate-950">Positioning</h2>
                        <p class="mt-1 text-sm text-slate-500">Features and differentiators that should appear in the final page.</p>
                    </div>

                    <div class="mt-5 grid gap-5">
                        <div>
                            <x-input-label for="key_features" value="Key features" />
                            <textarea id="key_features" name="key_features" rows="4" placeholder="Fast onboarding, Automated follow-up, Team analytics" class="form-field">{{ old('key_features', $salesPage->key_features) }}</textarea>
                            <x-input-error :messages="$errors->get('key_features')" />
                        </div>

                        <div>
                            <x-input-label for="unique_selling_points" value="Unique selling points" />
                            <textarea id="unique_selling_points" name="unique_selling_points" rows="3" class="form-field">{{ old('unique_selling_points', $salesPage->unique_selling_points) }}</textarea>
                            <x-input-error :messages="$errors->get('unique_selling_points')" />
                        </div>
                    </div>
                </section>

                <section>
                    <div class="border-b border-slate-200 pb-4">
                        <h2 class="text-base font-bold text-slate-950">Presentation</h2>
                        <p class="mt-1 text-sm text-slate-500">Choose the page style and copy tone.</p>
                    </div>

                    <div class="mt-5 grid gap-5 md:grid-cols-2">
                        <div>
                            <x-input-label for="template" value="Design template" />
                            <select id="template" name="template" class="form-field">
                                @foreach (['modern' => 'Modern', 'elegant' => 'Elegant', 'bold' => 'Bold'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('template', $salesPage->template ?: 'modern') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('template')" />
                        </div>

                        <div>
                            <x-input-label for="tone" value="Tone" />
                            <select id="tone" name="tone" class="form-field">
                                @foreach (['professional' => 'Professional', 'friendly' => 'Friendly', 'persuasive' => 'Persuasive'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('tone', $salesPage->tone ?: 'professional') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('tone')" />
                        </div>
                    </div>
                </section>
            </div>

            <div class="flex flex-col gap-3 border-t border-slate-200 bg-slate-50 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <p class="text-sm font-medium text-slate-600" x-show="generating" x-cloak>
                    Generating structured page content...
                </p>
                <p class="text-sm text-slate-500" x-show="! generating">
                    {{ $mode === 'edit' ? 'Regeneration will replace the saved AI content.' : 'The page will be saved after generation.' }}
                </p>
                <button type="submit" class="btn-primary w-full sm:w-auto" x-bind:disabled="generating">
                    <span x-show="generating" x-cloak class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                    <span x-show="! generating">{{ $mode === 'edit' ? 'Regenerate Page' : 'Generate Page' }}</span>
                    <span x-show="generating" x-cloak>Generating...</span>
                </button>
            </div>
        </form>

        <aside class="space-y-4 lg:sticky lg:top-24 lg:self-start">
            <div class="panel p-5">
                <p class="eyebrow">Output</p>
                <h2 class="mt-2 text-lg font-bold text-slate-950">Structured landing page</h2>
                <div class="mt-5 space-y-3 text-sm text-slate-600">
                    <div class="flex items-center gap-3">
                        <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                        <span>Hero copy and CTA</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                        <span>Benefits and feature breakdown</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                        <span>Pricing and social proof placeholder</span>
                    </div>
                </div>
            </div>
            <div class="panel p-5">
                <p class="eyebrow">Templates</p>
                <div class="mt-4 grid gap-3">
                    <div class="rounded-lg border border-slate-200 p-3">
                        <p class="font-semibold text-slate-950">Modern</p>
                        <p class="mt-1 text-sm text-slate-600">Clean SaaS styling with a focused conversion flow.</p>
                    </div>
                    <div class="rounded-lg border border-slate-200 p-3">
                        <p class="font-semibold text-slate-950">Elegant</p>
                        <p class="mt-1 text-sm text-slate-600">Softer premium styling for service-led offers.</p>
                    </div>
                    <div class="rounded-lg border border-slate-200 p-3">
                        <p class="font-semibold text-slate-950">Bold</p>
                        <p class="mt-1 text-sm text-slate-600">High-contrast styling for direct-response pages.</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</x-app-layout>
