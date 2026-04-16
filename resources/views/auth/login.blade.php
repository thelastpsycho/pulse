<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Log in - {{ config('app.name', 'Pulse') }}</title>

    <!-- Fonts -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-12 antialiased bg-background">

    <!-- Ambient Background Effects -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-0 w-96 h-96 bg-primary/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-accent/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="w-full max-w-sm relative z-10">
        <!-- Theme Toggle -->
        <div class="flex justify-end mb-6">
            <button
                onclick="toggleTheme()"
                class="p-2.5 rounded-xl glass text-muted hover:text-text transition-smooth cursor-pointer"
                title="Toggle theme"
                aria-label="Toggle dark/light theme"
            >
                <svg class="theme-icon-dark w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <svg class="theme-icon-light w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>
        </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-primary/20 to-accent/20 mb-5 ring-1 ring-primary/30">
                <img src="{{ asset('images/logo/guestpulse_app_icon.svg') }}" alt="Pulse Logo" class="w-10 h-10">
            </div>
            <h1 class="text-3xl font-bold text-text mb-2 tracking-tight">Welcome back</h1>
            <p class="text-base text-muted">Sign in to manage hotel operations</p>
        </div>

        <!-- Login Card -->
        <div class="glass rounded-2xl p-6 mb-5">
            @if (session('status'))
                <div class="mb-4 px-4 py-3 rounded-xl bg-success/10 border border-success/30 text-success text-sm transition-smooth" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-text mb-2">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="you@example.com"
                        class="w-full px-4 py-3 bg-surface-2 border border-border rounded-xl text-sm text-text placeholder-muted/60 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                    >
                    @error('email')
                        <p class="mt-2 text-xs text-danger flex items-center gap-1.5" role="alert">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-text mb-2">Password</label>
                    <div class="relative">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Enter your password"
                            class="w-full px-4 py-3 pr-12 bg-surface-2 border border-border rounded-xl text-sm text-text placeholder-muted/60 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                        >
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 p-2 text-muted hover:text-text transition-smooth cursor-pointer"
                            aria-label="Toggle password visibility"
                        >
                            <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-xs text-danger flex items-center gap-1.5" role="alert">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2.5 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-border bg-surface-2 text-primary focus:ring-primary/50 focus:ring-offset-0 cursor-pointer">
                        <span class="text-sm text-muted group-hover:text-text transition-smooth">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-primary hover:text-primary/80 font-medium transition-smooth cursor-pointer">Forgot password?</a>
                    @endif
                </div>

                <!-- Submit -->
                <button type="submit" class="btn w-full py-3.5">
                    Sign in
                </button>
            </form>
        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-muted/60 mt-8">
            &copy; {{ now()->year }} Pulse — Hotel Operations System
        </p>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');

            if (password.type === 'password') {
                password.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                password.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');

            if (isDark) {
                html.classList.remove('dark');
                html.classList.add('light');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.remove('light');
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }

            // Update theme icons
            const darkIcons = document.querySelectorAll('.theme-icon-dark');
            const lightIcons = document.querySelectorAll('.theme-icon-light');
            const shouldBeDark = html.classList.contains('dark');

            darkIcons.forEach(icon => icon.classList.toggle('hidden', !shouldBeDark));
            lightIcons.forEach(icon => icon.classList.toggle('hidden', shouldBeDark));
        }

        // Initialize theme from localStorage or system preference
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (savedTheme === 'light' || (!savedTheme && !prefersDark)) {
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
                document.querySelectorAll('.theme-icon-dark').forEach(icon => icon.classList.add('hidden'));
                document.querySelectorAll('.theme-icon-light').forEach(icon => icon.classList.remove('hidden'));
            }
        })();
    </script>
</body>
</html>
