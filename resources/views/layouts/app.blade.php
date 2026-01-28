<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS CDN (for production compatibility) -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Theme Variables -->
        <style>
            :root {
                --accent-rgb: 16 185 129;
            }

            .bg-primary { background-color: rgb(var(--accent-rgb)); }
            .hover\:bg-primary:hover { background-color: rgb(var(--accent-rgb)); }
            .text-primary { color: rgb(var(--accent-rgb)); }
            .border-primary { border-color: rgb(var(--accent-rgb)); }
            .ring-primary { --tw-ring-color: rgb(var(--accent-rgb)); }
            .focus\:ring-primary:focus { --tw-ring-color: rgb(var(--accent-rgb)); }
            .focus\:border-primary:focus { border-color: rgb(var(--accent-rgb)); }
        </style>
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $content ?? $slot ?? '' }}
                @yield('content')
            </main>
        </div>

        <script>
            (() => {
                const themes = {
                    emerald: { accent: [16, 185, 129] },
                    cyan: { accent: [6, 182, 212] },
                    violet: { accent: [139, 92, 246] },
                    amber: { accent: [245, 158, 11] },
                    rose: { accent: [244, 63, 94] }
                };

                const defaultTheme = 'emerald';

                const applyTheme = (key) => {
                    const theme = themes[key] || themes[defaultTheme];
                    const root = document.documentElement;
                    root.style.setProperty('--accent-rgb', theme.accent.join(' '));
                    root.setAttribute('data-theme', key);
                    localStorage.setItem('theme', key);

                    document.querySelectorAll('[data-theme-option]').forEach((btn) => {
                        btn.setAttribute('aria-pressed', btn.dataset.themeOption === key ? 'true' : 'false');
                    });
                };

                document.addEventListener('DOMContentLoaded', () => {
                    const saved = localStorage.getItem('theme') || defaultTheme;
                    applyTheme(saved);

                    document.querySelectorAll('[data-theme-option]').forEach((btn) => {
                        btn.addEventListener('click', () => applyTheme(btn.dataset.themeOption));
                    });
                });
            })();
        </script>
    </body>
</html>
