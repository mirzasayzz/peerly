<x-app-layout>
    {{-- Hero Section --}}
    @guest
    <div class="card-glass" style="background: var(--gradient); padding: 40px 32px; margin-bottom: 28px; text-align: center;">
        <h1 style="font-size: 28px; font-weight: 800; color: white; margin-bottom: 8px;">Welcome to Peerly 🎓</h1>
        <p style="color: rgba(255,255,255,0.8); font-size: 15px; max-width: 500px; margin: 0 auto;">
            Your community hub to discuss, share resources, and connect with fellow students.
        </p>
        <div style="margin-top: 20px;">
            <a href="{{ route('register') }}" class="btn" style="background: white; color: var(--accent); font-weight: 600;">
                <i class="ph ph-user-plus"></i> Join the Community
            </a>
        </div>
    </div>
    @endguest

    {{-- Sort Tabs --}}
    <div class="flex items-center justify-between mb-16">
        <div class="tab-nav" style="border-bottom: none; margin-bottom: 0;">
            <a href="{{ url('/') }}" class="tab-link {{ $sort === 'trending' ? 'active' : '' }}"><i class="ph ph-fire"></i> Trending</a>
            <a href="{{ url('/?sort=new') }}" class="tab-link {{ $sort === 'new' ? 'active' : '' }}"><i class="ph ph-clock"></i> Latest</a>
            <a href="{{ url('/?sort=top') }}" class="tab-link {{ $sort === 'top' ? 'active' : '' }}"><i class="ph ph-arrow-fat-up"></i> Top</a>
            <a href="{{ url('/?sort=unanswered') }}" class="tab-link {{ $sort === 'unanswered' ? 'active' : '' }}"><i class="ph ph-question"></i> Unanswered</a>
        </div>
        @auth
        <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">
            <i class="ph ph-plus"></i> New Post
        </a>
        @endauth
    </div>

    {{-- Posts Feed --}}
    <div class="card-flat" style="padding: 0; overflow: hidden;">
        @forelse($posts as $post)
            <div class="post-card" data-voteable="post-{{ $post->id }}">
                {{-- Vote Column --}}
                <div class="vote-col">
                    <button class="vote-btn vote-up"
                            onclick="vote('post', {{ $post->id }}, 1)" title="Upvote">
                        <i class="ph-bold ph-arrow-fat-up"></i>
                    </button>
                    <span class="vote-count">{{ $post->votes_sum_value ?? 0 }}</span>
                    <button class="vote-btn vote-down"
                            onclick="vote('post', {{ $post->id }}, -1)" title="Downvote">
                        <i class="ph-bold ph-arrow-fat-down"></i>
                    </button>
                </div>

                {{-- Post Content --}}
                <div class="post-content">
                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                        @if($post->is_pinned)
                            <span class="tag" style="background: var(--warning-soft); color: var(--warning); font-size: 11px;">
                                <i class="ph ph-push-pin"></i> Pinned
                            </span>
                        @endif
                        @if($post->is_resolved)
                            <span class="tag" style="background: var(--success-soft); color: var(--success); font-size: 11px;">
                                <i class="ph ph-check-circle"></i> Resolved
                            </span>
                        @endif
                    </div>

                    <a href="{{ route('posts.show', $post->slug) }}" class="post-title">{{ $post->title }}</a>

                    <p class="post-excerpt">{{ Str::limit(strip_tags($post->body), 150) }}</p>

                    <div class="tag-list mb-8">
                        @foreach($post->tags as $tag)
                            <a href="{{ route('search', ['tag' => $tag->slug]) }}" class="tag">{{ $tag->name }}</a>
                        @endforeach
                    </div>

                    <div class="post-meta">
                        <a href="{{ route('profile.show', $post->user->username ?? $post->user->id) }}" class="user-badge">
                            <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="avatar avatar-sm">
                            <span class="badge-name">{{ $post->user->name }}</span>
                        </a>
                        <span><i class="ph ph-chat-circle"></i> {{ $post->comments_count }} replies</span>
                        <span><i class="ph ph-eye"></i> {{ $post->view_count }} views</span>
                        <span>
                            <i class="ph ph-folder"></i>
                            <a href="{{ route('forums.show', $post->forum->slug) }}">{{ $post->forum->name }}</a>
                        </span>
                        <span><i class="ph ph-clock"></i> {{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon"><i class="ph ph-chats-circle"></i></div>
                <h3>No discussions yet</h3>
                <p>Be the first to start a conversation!</p>
                @auth
                <a href="{{ route('posts.create') }}" class="btn btn-primary">Create First Post</a>
                @endauth
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-24">
        {{ $posts->withQueryString()->links() }}
    </div>
</x-app-layout>
