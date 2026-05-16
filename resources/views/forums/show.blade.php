<x-app-layout>
    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ url('/') }}"><i class="ph ph-house"></i></a>
        <span class="sep">/</span>
        <a href="{{ route('forums.index') }}">Forums</a>
        <span class="sep">/</span>
        <a href="{{ route('forums.show', $forum->slug) }}">{{ $forum->category->name }}</a>
        <span class="sep">/</span>
        <span style="color: var(--text-primary);">{{ $forum->name }}</span>
    </div>

    {{-- Forum Header --}}
    <div class="card-flat flex items-center justify-between mb-24">
        <div>
            <h1 style="font-size: 22px; font-weight: 700;">{{ $forum->name }}</h1>
            @if($forum->description)
                <p class="text-sm text-muted mt-4">{{ $forum->description }}</p>
            @endif
        </div>
        @auth
        <a href="{{ route('posts.create') }}?forum={{ $forum->id }}" class="btn btn-primary btn-sm">
            <i class="ph ph-plus"></i> New Post
        </a>
        @endauth
    </div>

    {{-- Posts --}}
    <div class="card-flat" style="padding: 0; overflow: hidden;">
        @forelse($posts as $post)
            <div class="post-card" data-voteable="post-{{ $post->id }}">
                <div class="vote-col">
                    <button class="vote-btn vote-up {{ $post->user_vote === 1 ? 'active-up' : '' }}" onclick="vote('post', {{ $post->id }}, 1)">
                        <i class="ph-bold ph-arrow-fat-up"></i>
                    </button>
                    <span class="vote-count">{{ $post->votes_sum_value ?? 0 }}</span>
                    <button class="vote-btn vote-down {{ $post->user_vote === -1 ? 'active-down' : '' }}" onclick="vote('post', {{ $post->id }}, -1)">
                        <i class="ph-bold ph-arrow-fat-down"></i>
                    </button>
                </div>
                <div class="post-content">
                    @if($post->is_pinned)
                        <span class="tag mb-4" style="background: var(--warning-soft); color: var(--warning); font-size: 11px;">
                            <i class="ph ph-push-pin"></i> Pinned
                        </span>
                    @endif
                    <a href="{{ route('posts.show', $post->slug) }}" class="post-title">{{ $post->title }}</a>
                    <div class="tag-list mb-8">
                        @foreach($post->tags as $tag)
                            <a href="{{ route('search', ['tag' => $tag->slug]) }}" class="tag">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                    <div class="post-meta">
                        <a href="{{ route('profile.show', $post->user->username ?? $post->user->id) }}" class="user-badge">
                            <img src="{{ $post->user->avatar_url }}" alt="" class="avatar avatar-sm">
                            <span class="badge-name">{{ $post->user->name }}</span>
                        </a>
                        <span><i class="ph ph-chat-circle"></i> {{ $post->comments_count }}</span>
                        <span><i class="ph ph-eye"></i> {{ $post->view_count }}</span>
                        <span>{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon"><i class="ph ph-note-blank"></i></div>
                <h3>No posts in this forum</h3>
                <p>Start the first discussion!</p>
            </div>
        @endforelse
    </div>

    <div class="mt-24">{{ $posts->links() }}</div>
</x-app-layout>
