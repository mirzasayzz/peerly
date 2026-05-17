<x-guest-layout>
    <div class="mb-24">
        <h2 style="font-size: 24px; font-weight: 700; text-align: center;">Create Account</h2>
        <p class="auth-subtitle">Step 3 of 3: Almost there! Set up your profile.</p>
    </div>

    <form method="POST" action="{{ route('register.step3') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="form-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="form-error mt-4" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="form-error mt-4" />
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="form-error mt-4" />
        </div>

        <div class="mt-24">
            <button type="submit" class="btn btn-primary w-full" style="height: 44px; font-size: 15px;">
                {{ __('Create Account') }}
            </button>
        </div>
    </form>
</x-guest-layout>
