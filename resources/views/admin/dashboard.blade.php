<x-admin-layout>
    <h1 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">
        <i class="ph ph-shield-check" style="color: var(--accent);"></i> Admin Dashboard
    </h1>

    @if(session('status'))
        <div style="padding: 12px; background: var(--success-soft); color: var(--success); border-radius: 8px; margin-bottom: 24px;">
            {{ session('status') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; margin-bottom: 32px;">
        <div class="card" style="text-align: center;">
            <div style="font-size: 32px; font-weight: 800; background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                {{ \App\Models\User::count() }}
            </div>
            <div class="text-sm text-muted">Members</div>
        </div>
        <div class="card" style="text-align: center;">
            <div style="font-size: 32px; font-weight: 800; color: var(--accent);">{{ \App\Models\Post::count() }}</div>
            <div class="text-sm text-muted">Posts</div>
        </div>
        <div class="card" style="text-align: center;">
            <div style="font-size: 32px; font-weight: 800; color: var(--accent2);">{{ \App\Models\Comment::count() }}</div>
            <div class="text-sm text-muted">Comments</div>
        </div>
        <div class="card" style="text-align: center;">
            <div style="font-size: 32px; font-weight: 800; color: var(--warning);">{{ \App\Models\Report::where('status', 'pending')->count() }}</div>
            <div class="text-sm text-muted">Pending Reports</div>
        </div>
    </div>

    @if(auth()->user()->email === 'tubamirza822@gmail.com')
        <div class="card mb-24">
            <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 16px;">Manage Administrators</h2>
            <form action="{{ route('admin.manage.add') }}" method="POST" style="display: flex; gap: 12px; margin-bottom: 24px;">
                @csrf
                <input type="email" name="email" placeholder="User's email address" required style="flex: 1; padding: 10px; border: 1px solid var(--border); border-radius: 8px; background: var(--bg-tertiary); color: var(--text-primary);">
                <button type="submit" class="btn btn-primary" style="background: var(--accent); color: white;">Add Admin</button>
            </form>

            <div style="border: 1px solid var(--border); border-radius: 8px; overflow: hidden;">
                @foreach($admins as $adminUser)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; border-bottom: 1px solid var(--border);">
                        <div>
                            <span style="font-weight: 600;">{{ $adminUser->name }}</span> 
                            <span class="text-muted" style="margin-left: 8px;">{{ $adminUser->email }}</span>
                            @if($adminUser->email === 'tubamirza822@gmail.com')
                                <span class="badge" style="background: var(--danger-soft); color: var(--danger); font-size: 11px; margin-left: 8px; padding: 2px 6px; border-radius: 4px;">Super Admin</span>
                            @endif
                        </div>
                        @if($adminUser->email !== 'tubamirza822@gmail.com')
                            <form action="{{ route('admin.manage.remove', $adminUser) }}" method="POST" onsubmit="return confirm('Remove admin rights?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="color: var(--danger); background: var(--danger-soft);">Remove</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</x-admin-layout>
