<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="eyebrow">Dashboard</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">Sales page workspace</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Generate, review, and export conversion-focused landing pages from one place.
                </p>
            </div>
            <a href="{{ route('sales-pages.create') }}" class="btn-primary w-full sm:w-auto">Generate Sales Page</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="stat-card">
                <p class="text-sm font-semibold text-slate-500">Total pages</p>
                <p class="mt-3 text-3xl font-bold text-slate-950">{{ $totalPages }}</p>
            </div>
            <div class="stat-card">
                <p class="text-sm font-semibold text-slate-500">Modern</p>
                <p class="mt-3 text-3xl font-bold text-slate-950">{{ $modernCount }}</p>
            </div>
            <div class="stat-card">
                <p class="text-sm font-semibold text-slate-500">Elegant</p>
                <p class="mt-3 text-3xl font-bold text-slate-950">{{ $elegantCount }}</p>
            </div>
            <div class="stat-card">
                <p class="text-sm font-semibold text-slate-500">Bold</p>
                <p class="mt-3 text-3xl font-bold text-slate-950">{{ $boldCount }}</p>
            </div>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_360px]">
            <section class="panel overflow-hidden">
                <div class="border-b border-slate-200 px-5 py-4 sm:px-6">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-base font-bold text-slate-950">Recent pages</h2>
                            <p class="mt-1 text-sm text-slate-500">Latest generated landing pages.</p>
                        </div>
                        <a href="{{ route('sales-pages.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">View all</a>
                    </div>
                </div>

                @if ($recentPages->isEmpty())
                    <div class="px-6 py-14 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-50 text-lg font-black text-indigo-600">AI</div>
                        <h3 class="mt-4 text-base font-bold text-slate-950">No generated pages yet</h3>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-600">
                            Create your first sales page and it will appear here for quick access.
                        </p>
                        <a href="{{ route('sales-pages.create') }}" class="btn-primary mt-6">Create first page</a>
                    </div>
                @else
                    <div class="divide-y divide-slate-200">
                        @foreach ($recentPages as $salesPage)
                            <article class="flex flex-col gap-4 px-5 py-4 transition hover:bg-slate-50 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="truncate text-sm font-bold text-slate-950">{{ $salesPage->product_name }}</h3>
                                        <span class="badge">{{ $salesPage->template ?: 'modern' }}</span>
                                    </div>
                                    <p class="mt-1 line-clamp-1 text-sm text-slate-600">{{ $salesPage->headline() }}</p>
                                </div>
                                <div class="flex shrink-0 gap-2">
                                    <a href="{{ route('sales-pages.show', $salesPage) }}" class="btn-secondary">Preview</a>
                                    <a href="{{ route('sales-pages.edit', $salesPage) }}" class="btn-secondary">Edit</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>

            <aside class="panel p-5">
                <p class="eyebrow">Quick start</p>
                <h2 class="mt-2 text-lg font-bold text-slate-950">From inputs to export</h2>
                <div class="mt-5 space-y-4">
                    <div class="flex gap-3">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-sm font-bold text-indigo-700">1</span>
                        <p class="text-sm leading-6 text-slate-600">Add product details, audience, pricing, and positioning.</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-sm font-bold text-indigo-700">2</span>
                        <p class="text-sm leading-6 text-slate-600">Generate structured copy using AI or the built-in fallback.</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-sm font-bold text-indigo-700">3</span>
                        <p class="text-sm leading-6 text-slate-600">Preview the page and export a standalone HTML file.</p>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>
