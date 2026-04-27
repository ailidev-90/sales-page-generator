<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-950">Login</h1>
        <p class="mt-2 text-sm text-slate-600">Access your generated sales pages.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-700">
            <input type="checkbox" name="remember" class="rounded border-slate-300 text-slate-950 focus:ring-slate-950">
            Remember me
        </label>

        <div class="flex items-center justify-between gap-4">
            <a class="text-sm font-semibold text-slate-600 hover:text-slate-950" href="{{ route('register') }}">
                Create account
            </a>

            <x-primary-button>
                Login
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
