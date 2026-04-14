<x-guest-layout>
    <div class="text-center">
        <!-- Icon -->
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-accent/20 mb-5">
            <svg class="h-7 w-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </div>

        <!-- Heading -->
        <h2 class="text-xl font-semibold text-text mb-2">
            Password Reset Assistance
        </h2>

        <!-- Message -->
        <div class="bg-surface-2 border border-border rounded-lg p-5 mb-5">
            <p class="text-sm text-text mb-3">
                For security reasons, password resets can only be performed by the <strong>Super Admin</strong>.
            </p>
            <p class="text-sm text-text mb-3">
                To reset your password, please contact your system administrator directly.
            </p>

            <div class="border-t border-border pt-3 mt-3">
                <p class="text-sm text-muted mb-2">Contact Information:</p>
                <div class="flex items-center gap-2 text-sm text-text">
                    <svg class="h-4 w-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="font-medium">Super Admin</span>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="text-left bg-surface-1 border border-border rounded-lg p-4 mb-5">
            <h3 class="font-medium text-text mb-2 flex items-center gap-2">
                <svg class="h-4 w-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                How to proceed:
            </h3>
            <ol class="list-decimal list-inside space-y-1.5 text-sm text-muted">
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
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Login
            </a>
        </div>
    </div>
</x-guest-layout>
