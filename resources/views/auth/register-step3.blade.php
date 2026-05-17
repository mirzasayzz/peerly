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
            <p id="password-match-msg" style="font-size: 12px; margin-top: 5px; display: none;"></p>
            <x-input-error :messages="$errors->get('password_confirmation')" class="form-error mt-4" />
        </div>

        <div class="mt-24">
            <button type="submit" id="submit-btn" class="btn btn-primary w-full" style="height: 44px; font-size: 15px;">
                {{ __('Create Account') }}
            </button>
        </div>
    </form>

    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const matchMsg = document.getElementById('password-match-msg');
        const submitBtn = document.getElementById('submit-btn');

        function checkPasswords() {
            if (confirmPassword.value.length === 0) {
                matchMsg.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                return;
            }
            
            matchMsg.style.display = 'block';
            if (password.value === confirmPassword.value) {
                matchMsg.textContent = '✓ Passwords match';
                matchMsg.style.color = '#4ade80';
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
            } else {
                matchMsg.textContent = '✗ Passwords do not match';
                matchMsg.style.color = 'var(--danger)';
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
            }
        }

        password.addEventListener('input', checkPasswords);
        confirmPassword.addEventListener('input', checkPasswords);
    </script>
</x-guest-layout>
