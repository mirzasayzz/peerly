<x-app-layout>
    <h1 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">
        <i class="ph ph-shield-check" style="color: var(--accent);"></i> Admin Dashboard
    </h1>

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

    <p class="text-muted">Full moderation panel coming soon.</p>
</x-app-layout>
