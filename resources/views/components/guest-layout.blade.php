<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'AI Sales Page Generator') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900">
        <div class="flex min-h-screen flex-col items-center justify-center bg-slate-50 px-4 py-12">
            <a href="/" class="mb-8 text-lg font-extrabold tracking-tight text-slate-950">
                AI Sales Page Generator
            </a>

            <div class="w-full max-w-md rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
