<x-app-layout>
    <h1 style="font-size: 24px; font-weight: 700; margin-bottom: 28px;">
        <i class="ph ph-gear" style="color: var(--accent);"></i> Account Settings
    </h1>

    <div style="display: grid; gap: 24px; max-width: 700px;">

        {{-- Profile Information --}}
        <div class="card-flat">
            <h2 style="font-size: 17px; font-weight: 600; margin-bottom: 4px;"><i class="ph ph-user-circle"></i> Profile Information</h2>
            <p class="text-sm text-muted mb-24">Update your public profile and contact details.</p>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf @method('patch')

                <div class="form-group mb-24">
                    <label class="form-label" for="avatar">Profile Picture</label>
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <img src="{{ $user->avatar_url }}" alt="Avatar" style="width: 64px; height: 64px; border-radius: var(--radius-full); object-fit: cover; border: 2px solid var(--border);">
                        <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*" style="padding-top: 8px;">
                    </div>
                    @error('avatar') <div class="form-error mt-4">{{ $message }}</div> @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label" for="name">Full Name</label>
                        <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-input" value="{{ old('username', $user->username) }}" required>
                        @error('username') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" name="email" id="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="bio">Bio</label>
                    <textarea name="bio" id="bio" class="form-textarea" rows="3" placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label" for="university">University</label>
                        <input type="text" name="university" id="university" class="form-input" value="{{ old('university', $user->university) }}" placeholder="e.g. FAST NUCES">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="major">Major</label>
                        <input type="text" name="major" id="major" class="form-input" value="{{ old('major', $user->major) }}" placeholder="e.g. Computer Science">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="year">Year</label>
                        <select name="year" id="year" class="form-select">
                            <option value="">Select...</option>
                            @foreach(['1st Year','2nd Year','3rd Year','4th Year','Graduate','Alumni'] as $y)
                                <option value="{{ $y }}" {{ old('year', $user->year) === $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="github">GitHub</label>
                    <input type="url" name="github" id="github" class="form-input" value="{{ old('github', $user->github) }}" placeholder="https://github.com/username">
                </div>

                <div class="flex justify-between items-center" style="padding-top: 16px; border-top: 1px solid var(--border);">
                    @if (session('status') === 'profile-updated')
                        <span style="color: var(--success); font-size: 13px; font-weight: 500;"><i class="ph ph-check-circle"></i> Saved successfully!</span>
                    @else
                        <span></span>
                    @endif
                    <button type="submit" class="btn btn-gradient"><i class="ph ph-floppy-disk"></i> Save Changes</button>
                </div>
            </form>
        </div>

        {{-- Update Password --}}
        <div class="card-flat">
            <h2 style="font-size: 17px; font-weight: 600; margin-bottom: 4px;"><i class="ph ph-lock"></i> Update Password</h2>
            <p class="text-sm text-muted mb-24">Use a strong, unique password to keep your account secure.</p>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf @method('put')

                <div class="form-group">
                    <label class="form-label" for="current_password">Current Password</label>
                    <input type="password" name="current_password" id="current_password" class="form-input" placeholder="••••••••" autocomplete="current-password">
                    @error('current_password', 'updatePassword') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label" for="password">New Password</label>
                        <input type="password" name="password" id="password" class="form-input" placeholder="Min 8 characters" autocomplete="new-password">
                        @error('password', 'updatePassword') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" placeholder="••••••••" autocomplete="new-password">
                    </div>
                </div>

                <div style="text-align: right; padding-top: 16px; border-top: 1px solid var(--border);">
                    <button type="submit" class="btn btn-primary"><i class="ph ph-key"></i> Update Password</button>
                </div>
            </form>
        </div>

        {{-- Delete Account --}}
        <div class="card-flat" style="border-color: var(--danger-soft);">
            <h2 style="font-size: 17px; font-weight: 600; color: var(--danger); margin-bottom: 4px;"><i class="ph ph-warning"></i> Danger Zone</h2>
            <p class="text-sm text-muted mb-16">Once your account is deleted, all data will be permanently removed.</p>

            <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure? This action cannot be undone.');">
                @csrf @method('delete')
                <div class="form-group">
                    <label class="form-label" for="delete_password">Confirm Password to Delete</label>
                    <input type="password" name="password" id="delete_password" class="form-input" placeholder="Enter your password" required>
                    @error('password', 'userDeletion') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn btn-danger"><i class="ph ph-trash"></i> Delete Account</button>
            </form>
        </div>

    </div>
</x-app-layout>
