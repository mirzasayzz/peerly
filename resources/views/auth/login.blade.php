<x-guest-layout>
    <p class="auth-subtitle">Sign in to join the discussion</p>

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" required autofocus placeholder="you@university.edu">
            @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input type="password" name="password" id="password" class="form-input" required placeholder="••••••••">
            @error('password') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="flex justify-between items-center mb-16">
            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-secondary); cursor: pointer;">
                <input type="checkbox" name="remember"> Remember me
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size: 13px; color: var(--accent);">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn btn-gradient w-full" style="height: 48px; font-size: 15px;">
            <i class="ph ph-sign-in"></i> Sign In
        </button>
    </form>

    <div class="auth-footer">
        Don't have an account? <a href="{{ route('register') }}">Create one</a>
    </div>
</x-guest-layout>
