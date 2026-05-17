<x-guest-layout>
    <div class="mb-24 text-center">
        <h2 style="font-size: 24px; font-weight: 700; color: var(--danger);">Admin Portal</h2>
        <p class="auth-subtitle" style="margin-bottom: 16px;">Authorized Personnel Only</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-16" :status="session('status')" />

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <x-input-label for="email" :value="__('Admin Email')" />
            <x-text-input id="email" class="form-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="form-error mt-4" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="form-input" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="form-error mt-4" />
        </div>

        <div class="mt-24">
            <button type="submit" class="btn btn-primary w-full" style="height: 44px; font-size: 15px; background: var(--danger);">
                {{ __('Access Dashboard') }}
            </button>
        </div>
        
        <div class="auth-footer mt-16">
            <a href="{{ route('home') }}" style="color: var(--text-tertiary);"><i class="ph ph-arrow-left"></i> Return to Site</a>
        </div>
    </form>
</x-guest-layout>
