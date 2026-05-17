<x-guest-layout>
    <div class="mb-24">
        <h2 style="font-size: 24px; font-weight: 700; text-align: center;">Join the Community</h2>
        <p class="auth-subtitle">Step 1 of 3: Enter your email address to get started.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-16" :status="session('status')" />

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="form-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="form-error mt-4" />
        </div>

        <div class="mt-24">
            <button type="submit" class="btn btn-primary w-full" style="height: 44px; font-size: 15px;">
                {{ __('Continue with Email') }}
            </button>
        </div>
        
        <div class="auth-footer">
            Already registered? <a href="{{ route('login') }}">Sign In</a>
        </div>
    </form>
</x-guest-layout>
