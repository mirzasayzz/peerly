<x-guest-layout>
    <p class="auth-subtitle">Create your account and join the community</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="name">Full Name</label>
            <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" required placeholder="Tuba Mirza">
            @error('name') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="username">Username</label>
            <input type="text" name="username" id="username" class="form-input" value="{{ old('username') }}" required placeholder="tubamirza">
            @error('username') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" required placeholder="you@university.edu">
            @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input type="password" name="password" id="password" class="form-input" required placeholder="Min 8 characters">
            @error('password') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required placeholder="••••••••">
            <p id="password-match-msg" style="font-size: 12px; margin-top: 5px; display: none;"></p>
        </div>

        <button type="submit" id="submit-btn" class="btn btn-gradient w-full" style="height: 48px; font-size: 15px;">
            <i class="ph ph-user-plus"></i> Create Account
        </button>
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

    <div class="auth-footer">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
    </div>
</x-guest-layout>
