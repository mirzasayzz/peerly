<x-guest-layout>
    <div class="mb-24">
        <h2 style="font-size: 24px; font-weight: 700; text-align: center;">Verify Your Email</h2>
        <p class="auth-subtitle" style="margin-bottom: 8px;">Step 2 of 3: Enter the 6-digit code sent to {{ $email }}</p>
        <p class="text-muted text-center" style="font-size: 12px; margin-bottom: 32px;">(Please check your spam/junk folder if you don't see it in your inbox)</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-16" :status="session('status')" />

    <form method="POST" action="{{ route('register.step2') }}">
        @csrf

        <!-- OTP -->
        <div class="form-group">
            <x-input-label for="otp" :value="__('6-Digit Verification Code')" />
            <x-text-input id="otp" class="form-input" type="text" name="otp" required autofocus autocomplete="off" maxlength="6" style="text-align: center; font-size: 24px; letter-spacing: 4px;" />
            <x-input-error :messages="$errors->get('otp')" class="form-error mt-4" />
        </div>

        <div class="mt-24">
            <button type="submit" class="btn btn-primary w-full" style="height: 44px; font-size: 15px;">
                {{ __('Verify Code') }}
            </button>
        </div>
        
        <div class="auth-footer">
            <a href="{{ route('register') }}">Change Email Address</a>
        </div>
    </form>
</x-guest-layout>
