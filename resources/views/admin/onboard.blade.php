<x-guest-layout>
    <div style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 8px; justify-content: center; margin-bottom: 6px;">
            <i class="ph ph-shield-check" style="font-size: 20px; color: var(--accent);"></i>
            <h2 style="font-size: 22px; font-weight: 700;">Admin Account Setup</h2>
        </div>
        <p class="auth-subtitle" style="margin-bottom: 0;">Complete your invite to become a Peerly administrator</p>
    </div>

    <div style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: var(--bg-tertiary); border: 1px solid var(--border); border-radius: var(--radius-md); margin-bottom: 24px;">
        <i class="ph ph-envelope-simple" style="color: var(--accent); font-size: 16px; flex-shrink:0;"></i>
        <span style="font-size: 14px; font-weight: 600; color: var(--text-primary);">{{ $invitation->email }}</span>
    </div>

    @if($errors->any())
        <div style="padding: 12px; background: rgba(239,68,68,0.1); color: #ef4444; border-radius: 8px; margin-bottom: 16px; font-size: 13px;">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.onboard.complete', $token) }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="name">Full Name</label>
            <input type="text" id="name" name="name" class="form-input"
                   placeholder="e.g. Ahmed Khan"
                   value="{{ old('name') }}" required autofocus>
            <div style="font-size: 12px; color: var(--text-tertiary); margin-top: 4px;">This will be your display name on the platform</div>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input type="password" id="password" name="password" class="form-input"
                   placeholder="At least 8 characters" required autocomplete="new-password">
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                   placeholder="Repeat your password" required autocomplete="new-password">
            <p id="password-match-msg" style="font-size: 12px; margin-top: 5px; display: none;"></p>
        </div>

        <button type="submit" id="submit-btn" class="btn btn-primary w-full" style="height: 44px; font-size: 15px; margin-top: 8px;">
            <i class="ph ph-check-circle"></i> Activate My Admin Account
        </button>
    </form>

    <p class="auth-footer" style="margin-top: 16px;">You'll receive a confirmation email once your account is activated.</p>

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
                matchMsg.style.color = '#ef4444';
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
            }
        }

        password.addEventListener('input', checkPasswords);
        confirmPassword.addEventListener('input', checkPasswords);
    </script>
</x-guest-layout>
