<x-guest-layout>
    <div class="text-center">
        <!-- Icon -->
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-accent/20 mb-6">
            <svg class="h-8 w-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </div>

        <!-- Heading -->
        <h2 class="text-2xl font-bold text-text mb-2">
            Password Reset Assistance
        </h2>

        <!-- Message -->
        <div class="bg-surface-2 border border-border rounded-lg p-6 mb-6">
            <p class="text-text mb-4">
                For security reasons, password resets can only be performed by the <strong>Super Admin</strong>.
            </p>
            <p class="text-text mb-4">
                To reset your password, please contact your system administrator directly.
            </p>

            <div class="border-t border-border pt-4 mt-4">
                <p class="text-sm text-muted mb-2">Contact Information:</p>
                <div class="flex items-center gap-2 text-text">
                    <svg class="h-5 w-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="font-medium">Super Admin</span>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="text-left bg-surface-1 border border-border rounded-lg p-4 mb-6">
            <h3 class="font-medium text-text mb-2 flex items-center gap-2">
                <svg class="h-5 w-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                How to proceed:
            </h3>
            <ol class="list-decimal list-inside space-y-2 text-sm text-muted">
                <li>Contact your Super Admin via email, phone, or in person</li>
                <li>Request a password reset for your account</li>
                <li>Provide your registered email address for verification</li>
                <li>Wait for the admin to reset your password</li>
                <li>Use the temporary password provided to log in and change your password</li>
            </ol>
        </div>

        <!-- Back to Login Link -->
        <div class="text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-accent hover:text-accent/80 font-medium transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Login
            </a>
        </div>
    </div>
</x-guest-layout>
