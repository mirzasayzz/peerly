<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        // Intercept pending deletion requests
        if (\App\Models\AccountDeletionRequest::where('user_id', $user->id)->where('status', 'pending')->exists()) {
            Auth::logout();
            return redirect()->back()->withErrors([
                'email' => 'Your account is pending deletion and cannot be accessed.',
            ]);
        }

        if ($user->role === 'admin') {
            Auth::logout();
            return redirect()->back()->withErrors([
                'email' => 'Administrators must use the dedicated admin portal to log in.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
