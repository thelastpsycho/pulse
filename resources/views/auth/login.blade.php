<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Log in - {{ config('app.name', 'GuestPulse!') }}</title>

    <!-- Fonts -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-background min-h-screen flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-sm">
        <!-- Theme Toggle -->
        <div class="flex justify-end mb-4">
            <button
                onclick="toggleTheme()"
                class="p-2 rounded-lg text-muted hover:text-text transition-colors"
                title="Toggle theme"
            >
                <svg class="theme-icon-dark w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <svg class="theme-icon-light w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>
        </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary/10 mb-5">
                <img src="{{ asset('images/logo/guestpulse_app_icon.svg') }}" alt="GuestPulse!" class="w-12 h-12">
            </div>
            <h1 class="text-2xl font-bold text-text">Welcome back</h1>
            <p class="text-sm text-muted mt-1">Sign in to your GuestPulse! account</p>
        </div>

        <!-- Login Card -->
        <div class="bg-surface rounded-xl border border-border p-6">
            @if (session('status'))
                <div class="mb-5 px-3 py-2.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-text mb-1.5">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="you@example.com"
                        class="w-full px-3 py-2.5 bg-surface-2 border border-border rounded-lg text-sm text-text placeholder-muted/60 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-colors"
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-text mb-1.5">Password</label>
                    <div class="flex items-center bg-surface-2 border border-border rounded-lg focus-within:border-primary focus-within:ring-1 focus-within:ring-primary/30 transition-colors">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Enter your password"
                            class="flex-1 px-3 py-2.5 bg-transparent border-none text-sm text-text placeholder-muted/60 focus:outline-none focus:ring-0"
                        >
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="px-3 text-muted hover:text-text transition-colors"
                        >
                            <svg id="eye-open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eye-closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-border bg-surface-2 text-primary focus:ring-primary focus:ring-offset-0 focus:ring-1">
                        <span class="text-xs text-muted">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-primary hover:underline">Forgot password?</a>
                    @endif
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full py-3 bg-primary hover:bg-primary/90 text-primary-foreground text-sm font-semibold rounded-lg transition-colors">
                    Sign in
                </button>
            </form>
        </div>

        <!-- Demo Accounts -->
        <div class="mt-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="flex-1 h-px bg-border"></div>
                <span class="text-[11px] text-muted uppercase tracking-wide">Demo accounts</span>
                <div class="flex-1 h-px bg-border"></div>
            </div>

            <div class="grid grid-cols-3 gap-2">
                <button type="button" onclick="fillDemo('superadmin@example.com', 'password')" class="flex flex-col items-center gap-1.5 px-3 py-3 rounded-lg bg-surface border border-border hover:border-primary/40 transition-colors group">
                    <span class="w-8 h-8 rounded-full bg-rose-500/10 text-rose-500 flex items-center justify-center text-xs font-bold">SA</span>
                    <span class="text-[11px] text-muted group-hover:text-text transition-colors">Super Admin</span>
                </button>

                <button type="button" onclick="fillDemo('admin@example.com', 'password')" class="flex flex-col items-center gap-1.5 px-3 py-3 rounded-lg bg-surface border border-border hover:border-primary/40 transition-colors group">
                    <span class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-bold">A</span>
                    <span class="text-[11px] text-muted group-hover:text-text transition-colors">Admin</span>
                </button>

                <button type="button" onclick="fillDemo('staff@example.com', 'password')" class="flex flex-col items-center gap-1.5 px-3 py-3 rounded-lg bg-surface border border-border hover:border-primary/40 transition-colors group">
                    <span class="w-8 h-8 rounded-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-xs font-bold">S</span>
                    <span class="text-[11px] text-muted group-hover:text-text transition-colors">Staff</span>
                </button>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-muted/60 mt-8">
            &copy; {{ now()->year }} GuestPulse!
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

        function fillDemo(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }

        function toggleTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            if (isDark) {
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.remove('light');
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            const darkIcons = document.querySelectorAll('.theme-icon-dark');
            const lightIcons = document.querySelectorAll('.theme-icon-light');
            const shouldBeDark = document.documentElement.classList.contains('dark');
            darkIcons.forEach(icon => icon.classList.toggle('hidden', !shouldBeDark));
            lightIcons.forEach(icon => icon.classList.toggle('hidden', shouldBeDark));
        }
    </script>
</body>
</html>
