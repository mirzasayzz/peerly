<x-app-layout>
    <h1 style="font-size: 24px; font-weight: 700; margin-bottom: 8px;">
        <i class="ph ph-magnifying-glass" style="color: var(--accent);"></i>
        @if($query) Search: "{{ $query }}" @elseif($tagSlug) Tag: {{ $tagSlug }} @else Explore @endif
    </h1>

    <p class="text-sm text-muted mb-24">{{ $posts->total() }} results found</p>

    <div class="card-flat" style="padding: 0; overflow: hidden;">
        @forelse($posts as $post)
            <div class="post-card">
                <div class="post-content">
                    <a href="{{ route('posts.show', $post->slug) }}" class="post-title">{{ $post->title }}</a>
                    <p class="post-excerpt">{{ Str::limit(strip_tags($post->body), 120) }}</p>
                    <div class="post-meta">
                        <span class="user-badge">
                            <img src="{{ $post->user->avatar_url }}" alt="" class="avatar avatar-sm">
                            <span class="badge-name">{{ $post->user->name }}</span>
                        </span>
                        <span><i class="ph ph-chat-circle"></i> {{ $post->comments_count }}</span>
                        <span>{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon"><i class="ph ph-magnifying-glass"></i></div>
                <h3>No results found</h3>
                <p>Try a different search term.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-24">{{ $posts->withQueryString()->links() }}</div>
</x-app-layout>
