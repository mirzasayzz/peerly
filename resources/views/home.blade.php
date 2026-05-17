<x-app-layout>
    {{-- Hero Section --}}
    @guest
    <div class="card-glass hero-section" style="background: var(--gradient); padding: 40px 40px; margin-bottom: 32px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 30px; overflow: hidden; position: relative; border-radius: var(--radius-xl);">
        <div style="flex: 1; min-width: 0; text-align: left; z-index: 2; display: flex; flex-direction: column; justify-content: center;">
            <h1 style="font-size: 34px; font-weight: 800; color: white; margin-bottom: 16px; line-height: 1.2; letter-spacing: -0.5px;">Welcome to Peerly 🎓</h1>
            <p style="color: rgba(255,255,255,0.95); font-size: 16px; max-width: 480px; margin-bottom: 28px; line-height: 1.6;">
                Your ultimate community hub to ask questions, share academic resources, and connect with fellow students worldwide.
            </p>
            <div>
                <a href="{{ route('register') }}" class="btn hero-btn" style="background: white; color: var(--accent); font-weight: 700; padding: 14px 28px; font-size: 16px; border-radius: var(--radius-lg); transition: all 0.3s ease; display: inline-flex; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <i class="ph ph-users" style="font-size: 20px; margin-right: 6px;"></i> Join the Community
                </a>
            </div>
        </div>
        <div style="flex: 1; min-width: 0; z-index: 2; display: flex; justify-content: center; align-items: center;" class="hero-image-wrap">
            <img src="{{ asset('images/peerlycommunity.png') }}" alt="Peerly Community" style="max-width: 100%; height: auto; max-height: 280px; object-fit: contain; filter: drop-shadow(0px 15px 25px rgba(0,0,0,0.15)); transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);" class="hero-image">
        </div>
        <!-- Decorative background elements -->
        <div style="position: absolute; top: -50px; right: 10%; width: 250px; height: 250px; background: rgba(255,255,255,0.15); border-radius: 50%; filter: blur(30px); z-index: 1; pointer-events: none;"></div>
        <div style="position: absolute; bottom: -80px; left: -20px; width: 200px; height: 200px; background: rgba(0,0,0,0.1); border-radius: 50%; filter: blur(40px); z-index: 1; pointer-events: none;"></div>
    </div>
    
    <style>
        .hero-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.2) !important;
            background: #f8fafc !important;
        }
        .hero-btn:active { transform: translateY(0); }
        .hero-image:hover { transform: scale(1.08) translateY(-8px); }
        @media (max-width: 640px) {
            .hero-section {
                padding: 24px 20px !important;
                gap: 0 !important;
                justify-content: center !important;
            }
            .hero-section > div:first-child {
                min-width: 0 !important;
                text-align: center !important;
                align-items: center !important;
            }
            .hero-section h1 { font-size: 22px !important; margin-bottom: 10px !important; }
            .hero-section p { font-size: 13px !important; margin-bottom: 18px !important; }
            .hero-image-wrap { display: none !important; }
        }
        @media (min-width: 641px) and (max-width: 860px) {
            .hero-section {
                text-align: center !important;
                flex-direction: column-reverse;
                padding: 32px 24px !important;
                gap: 20px !important;
            }
            .hero-section > div:first-child {
                text-align: center !important;
                align-items: center !important;
                min-width: 0 !important;
            }
            .hero-image { max-height: 180px !important; }
        }
    </style>
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
                <div class="post-content" onclick="window.location='{{ route('posts.show', $post->slug) }}'" style="cursor: pointer;">
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

                    <p class="post-excerpt" style="pointer-events: none;">{{ Str::limit(strip_tags($post->body), 150) }}</p>

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
