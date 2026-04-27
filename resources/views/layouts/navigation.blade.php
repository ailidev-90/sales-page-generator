<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex">
                <div class="flex shrink-0 items-center">
                    <a href="{{ route('dashboard') }}" class="flex min-w-0 items-center gap-3 text-base font-extrabold text-slate-950">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-600 text-sm font-black text-white">AI</span>
                        <span class="max-w-[13rem] truncate sm:max-w-none">AI Sales Page Generator</span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>
                    <x-nav-link :href="route('sales-pages.create')" :active="request()->routeIs('sales-pages.create')">
                        Generate Sales Page
                    </x-nav-link>
                    <x-nav-link :href="route('sales-pages.index')" :active="request()->routeIs('sales-pages.index')">
                        Saved Pages
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:ms-6 sm:flex sm:items-center">
                <div class="me-4 text-sm font-medium text-slate-700">{{ Auth::user()->name }}</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 hover:text-slate-950">
                        Logout
                    </button>
                </form>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700 focus:bg-slate-100 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden border-t border-slate-200 sm:hidden">
        <div class="space-y-1 pb-3 pt-2">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('sales-pages.create')" :active="request()->routeIs('sales-pages.create')">
                Generate Sales Page
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('sales-pages.index')" :active="request()->routeIs('sales-pages.index')">
                Saved Pages
            </x-responsive-nav-link>
        </div>

        <div class="border-t border-slate-200 pb-3 pt-4">
            <div class="px-4 text-sm font-medium text-slate-700">{{ Auth::user()->name }}</div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="block w-full px-4 py-2 text-left text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>
