<x-app-layout>
    <h1 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;"><i class="ph ph-bookmark-simple" style="color: var(--accent);"></i> Bookmarks</h1>
    <div class="card-flat" style="padding: 0; overflow: hidden;">
        @forelse($bookmarks as $bm)
            <div class="post-card">
                <div class="post-content">
                    <a href="{{ route('posts.show', $bm->post->slug) }}" class="post-title">{{ $bm->post->title }}</a>
                    <div class="post-meta">
                        <span class="user-badge">
                            <img src="{{ $bm->post->user->avatar_url }}" alt="" class="avatar avatar-sm">
                            <span class="badge-name">{{ $bm->post->user->name }}</span>
                            @if($bm->post->user->isAdmin())
                                <span class="tag" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); font-size: 10px; padding: 2px 6px; line-height: 1; display: inline-flex; align-items: center; gap: 2px;">
                                    <i class="ph ph-shield" style="font-size: 10px;"></i> Admin
                                </span>
                            @endif
                        </span>
                        <span><i class="ph ph-chat-circle"></i> {{ $bm->post->comments_count }}</span>
                        <span>{{ $bm->post->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon"><i class="ph ph-bookmark-simple"></i></div>
                <h3>No bookmarks yet</h3>
                <p>Save posts to find them later.</p>
            </div>
        @endforelse
    </div>
    <div class="mt-24">{{ $bookmarks->links() }}</div>
</x-app-layout>
