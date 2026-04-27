<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="eyebrow">Library</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">Saved sales pages</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Search, preview, regenerate, export, or remove generated landing pages.
                </p>
            </div>
            <a href="{{ route('sales-pages.create') }}" class="btn-primary w-full sm:w-auto">Generate Sales Page</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="panel mb-6 p-4 sm:p-5">
            <form method="GET" action="{{ route('sales-pages.index') }}" class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_auto_auto] sm:items-center">
                <div>
                    <label for="search" class="sr-only">Search pages</label>
                    <input
                        id="search"
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search by product name or headline"
                        class="form-field mt-0"
                    >
                </div>
                <button type="submit" class="btn-secondary w-full sm:w-auto">Search</button>
                @if ($search !== '')
                    <a href="{{ route('sales-pages.index') }}" class="btn-secondary w-full sm:w-auto">Clear</a>
                @endif
            </form>
        </div>

        @if ($salesPages->isEmpty())
            <div class="panel px-6 py-16 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-lg bg-indigo-50 text-xl font-black text-indigo-600">AI</div>
                <div class="mx-auto mt-5 max-w-md">
                    <h2 class="text-lg font-bold text-slate-950">
                        {{ $search !== '' ? 'No matching pages found' : 'No saved pages yet' }}
                    </h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        {{ $search !== '' ? 'Try a different product name or headline.' : 'Generate your first landing page and it will be saved here automatically.' }}
                    </p>
                    <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
                        @if ($search !== '')
                            <a href="{{ route('sales-pages.index') }}" class="btn-secondary">View all pages</a>
                        @endif
                        <a href="{{ route('sales-pages.create') }}" class="btn-primary">Create page</a>
                    </div>
                </div>
            </div>
        @else
            <div class="mb-4 flex items-center justify-between gap-4">
                <p class="text-sm font-medium text-slate-600">
                    Showing {{ $salesPages->firstItem() }}-{{ $salesPages->lastItem() }} of {{ $salesPages->total() }} pages
                </p>
            </div>

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($salesPages as $salesPage)
                    <article class="panel flex min-h-full flex-col overflow-hidden transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="border-b border-slate-200 bg-slate-50 px-5 py-4">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <span class="badge">{{ ucfirst($salesPage->template ?: 'modern') }}</span>
                                    <h2 class="mt-3 truncate text-lg font-bold text-slate-950">{{ $salesPage->product_name }}</h2>
                                </div>
                                <span class="shrink-0 rounded-lg bg-white px-2.5 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">
                                    {{ ucfirst($salesPage->tone ?: 'professional') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-1 flex-col p-5">
                            <p class="line-clamp-2 text-sm leading-6 text-slate-600">{{ $salesPage->headline() }}</p>

                            <dl class="mt-5 grid grid-cols-2 gap-3 text-sm">
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <dt class="font-semibold text-slate-500">Audience</dt>
                                    <dd class="mt-1 line-clamp-2 font-medium text-slate-900">{{ $salesPage->target_audience }}</dd>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <dt class="font-semibold text-slate-500">Price</dt>
                                    <dd class="mt-1 line-clamp-2 font-medium text-slate-900">{{ $salesPage->price ?: 'Flexible' }}</dd>
                                </div>
                            </dl>

                            <div class="mt-5 text-xs font-medium text-slate-500">
                                Updated {{ $salesPage->updated_at->diffForHumans() }}
                            </div>

                            <div class="mt-5 grid gap-2 sm:grid-cols-2">
                                <a href="{{ route('sales-pages.show', $salesPage) }}" class="btn-primary">Preview</a>
                                <a href="{{ route('sales-pages.edit', $salesPage) }}" class="btn-secondary">Edit</a>
                                <a href="{{ route('sales-pages.export-html', $salesPage) }}" class="btn-secondary sm:col-span-2">Export HTML</a>
                                <form method="POST" action="{{ route('sales-pages.destroy', $salesPage) }}" onsubmit="return confirm('Delete this sales page?')" class="sm:col-span-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger w-full">Delete</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $salesPages->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
