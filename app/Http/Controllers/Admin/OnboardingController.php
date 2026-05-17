<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminInviteMail;
use App\Mail\AdminWelcomeMail;
use App\Models\AdminInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OnboardingController extends Controller
{
    // Show the onboarding form for a valid token
    public function show(string $token)
    {
        $invitation = AdminInvitation::where('token', $token)->first();

        if (!$invitation || !$invitation->isValid()) {
            abort(410, 'This invitation link has expired or is no longer valid.');
        }

        return view('admin.onboard', compact('invitation', 'token'));
    }

    // Handle onboarding form submission
    public function complete(Request $request, string $token)
    {
        $invitation = AdminInvitation::where('token', $token)->first();

        if (!$invitation || !$invitation->isValid()) {
            abort(410, 'This invitation link has expired or is no longer valid.');
        }

        $request->validate([
            'name'     => 'required|string|max:100',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check if user already exists
        $user = User::where('email', $invitation->email)->first();

        if ($user) {
            // Use existing username if present, otherwise generate a unique one
            if (empty($user->username)) {
                $baseUsername = Str::slug($request->name, '_');
                $username = preg_replace('/[^A-Za-z0-9_]/', '', $baseUsername);
                $counter = 1;
                while (User::where('username', $username)->exists()) {
                    $username = $baseUsername . $counter++;
                }
                $user->username = $username;
            }

            $user->name = $request->name;
            $user->password = Hash::make($request->password);
            $user->role = 'admin';
            $user->email_verified_at = now();
            $user->save();
        } else {
            // Generate a unique username for a new user
            $baseUsername = Str::slug($request->name, '_');
            $username = preg_replace('/[^A-Za-z0-9_]/', '', $baseUsername);
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter++;
            }

            $user = new User([
                'name'     => $request->name,
                'email'    => $invitation->email,
                'username' => $username,
                'password' => Hash::make($request->password),
                'role'     => 'admin',
            ]);
            $user->email_verified_at = now();
            $user->save();
        }

        // Mark invitation as completed
        $invitation->update(['status' => 'completed']);

        // Send welcome confirmation email
        Mail::to($user->email)->send(new AdminWelcomeMail($user->name));

        return redirect()->route('admin.login')
            ->with('status', 'Your admin account has been created! You can now log in.');
    }
}
