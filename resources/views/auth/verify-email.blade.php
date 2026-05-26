<x-guest-layout>
    <div style="color: var(--text-tertiary); font-size: 14px; margin-bottom: 24px;">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div style="padding: 12px 16px; background: var(--success-soft); border: 1px solid var(--success); border-radius: var(--radius-md); color: var(--success); font-size: 14px; margin-bottom: 16px;">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 20px;">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button style="height: 48px; font-size: 15px;">
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" style="font-size: 14px; color: var(--text-tertiary); transition: var(--transition);" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-tertiary)'">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>

