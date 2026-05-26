<x-guest-layout>
    @if (session('status'))
        {{-- ✅ Success state — hide form entirely --}}
        <div style="text-align: center; padding: 20px 0;">
            <div style="width: 64px; height: 64px; background: var(--success-soft); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <i class="ph ph-check-circle" style="font-size: 32px; color: var(--success);"></i>
            </div>
            <h2 style="font-size: 18px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">Check your inbox</h2>
            <p style="color: var(--text-tertiary); font-size: 14px; line-height: 1.6; margin-bottom: 24px;">
                We've sent a password reset link to your email. Please check your inbox and spam folder.
            </p>
            <a href="{{ route('login') }}" class="btn btn-primary" style="width: 100%; height: 48px; font-size: 15px;">
                <i class="ph ph-arrow-left"></i> Back to Sign In
            </a>
        </div>
    @else
        {{-- 📧 Form state --}}
        <div style="color: var(--text-tertiary); font-size: 14px; margin-bottom: 24px;">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <form method="POST" action="{{ route('password.email') }}" x-data="{ submitting: false }" x-on:submit="if (submitting) { $event.preventDefault(); return; } submitting = true;">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary w-full" style="height: 48px; font-size: 15px;" x-bind:disabled="submitting" x-bind:style="submitting ? 'opacity: 0.6; cursor: not-allowed;' : ''">
                    <span x-show="!submitting">{{ __('Email Password Reset Link') }}</span>
                    <span x-show="submitting" style="display: none;" x-bind:style="submitting ? 'display: inline-flex; align-items: center; gap: 8px;' : 'display: none;'">
                        <span class="spinner"></span> Sending...
                    </span>
                </button>
            </div>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ route('login') }}" style="font-size: 13px; color: var(--accent);">
                <i class="ph ph-arrow-left"></i> Back to Sign In
            </a>
        </div>
    @endif
</x-guest-layout>

