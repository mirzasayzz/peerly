<x-app-layout>
    <h1 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;"><i class="ph ph-bell" style="color: var(--accent);"></i> Notifications</h1>
    <div class="card-flat" style="padding: 0; overflow: hidden;">
        @forelse($notifications as $notif)
            <div class="post-card" style="{{ !$notif->read_at ? 'border-left: 3px solid var(--accent);' : '' }}">
                <div class="post-content">
                    <p class="text-sm">{{ $notif->data['message'] ?? 'You have a notification.' }}</p>
                    <span class="text-xs text-muted">{{ $notif->created_at->diffForHumans() }}</span>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon"><i class="ph ph-bell-slash"></i></div>
                <h3>No notifications</h3>
                <p>You're all caught up!</p>
            </div>
        @endforelse
    </div>
    <div class="mt-24">{{ $notifications->links() }}</div>
</x-app-layout>
