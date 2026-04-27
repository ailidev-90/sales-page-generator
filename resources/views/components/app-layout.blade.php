<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'AI Sales Page Generator') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans">
        <div class="min-h-screen bg-slate-50">
            @include('layouts.navigation')

            @isset($header)
                <header class="border-b border-slate-200 bg-white/95">
                    <div class="mx-auto max-w-7xl px-4 py-7 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                @if (session('success'))
                    <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
                        <div class="flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm">
                            <span class="mt-1 h-2 w-2 rounded-full bg-emerald-500"></span>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
                        <div class="flex items-start gap-3 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800 shadow-sm">
                            <span class="mt-1 h-2 w-2 rounded-full bg-rose-500"></span>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </body>
</html>
