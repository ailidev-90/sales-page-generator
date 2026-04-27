<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-slate-950">Profile</h1>
    </x-slot>

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('profile.update') }}" class="panel space-y-5 p-6">
            @csrf
            @method('PATCH')

            <div>
                <x-input-label for="name" value="Name" />
                <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required />
                <x-input-error :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required />
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>Save</x-primary-button>
                @if (session('status') === 'profile-updated')
                    <p class="text-sm font-medium text-emerald-700">Saved.</p>
                @endif
            </div>
        </form>
    </div>
</x-app-layout>
