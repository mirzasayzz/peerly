<x-app-layout>
    <div class="profile-header">
        <div class="profile-cover"></div>
        <div class="profile-info">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="avatar-xl">
            <div class="profile-details">
                <h1>{{ $user->name }}</h1>
                <div class="username">{{ '@' . ($user->username ?? $user->id) }}</div>
                <div class="flex items-center gap-12 mt-8" style="flex-wrap: wrap;">
                    @foreach($user->all_badges as $badge)
                        <span class="reputation-badge" style="color: {{ $badge['color'] }};">
                            {{ $badge['icon'] }} {{ $badge['label'] }}@if($badge['type'] === 'reputation') · {{ $user->reputation }} rep@endif
                        </span>
                    @endforeach
                    @if($user->university)
                        <span class="text-sm text-muted"><i class="ph ph-graduation-cap"></i> {{ $user->university }}</span>
                    @endif
                    @if($user->major)
                        <span class="text-sm text-muted"><i class="ph ph-book-open"></i> {{ $user->major }}</span>
                    @endif
                    @if($user->year)
                        <span class="text-sm text-muted"><i class="ph ph-calendar"></i> {{ $user->year }}</span>
                    @endif
                </div>
            </div>
            @auth
                @if(auth()->id() !== $user->id)
                    <form method="POST" action="{{ route('follow.toggle', $user) }}">
                        @csrf
                        <button class="btn {{ auth()->user()->isFollowing($user) ? 'btn-secondary' : 'btn-primary' }} btn-sm">
                            <i class="ph ph-{{ auth()->user()->isFollowing($user) ? 'user-minus' : 'user-plus' }}"></i>
                            {{ auth()->user()->isFollowing($user) ? 'Unfollow' : 'Follow' }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('profile.edit') }}" class="btn btn-secondary btn-sm">
                        <i class="ph ph-gear"></i> Edit Profile
                    </a>
                @endif
            @endauth
        </div>
    </div>

    @if($user->bio)
        <div class="card-flat mb-16">
            <p style="font-size: 14px; color: var(--text-secondary); line-height: 1.7;">{{ $user->bio }}</p>
        </div>
    @endif

    {{-- Social Links --}}
    @if($user->github || $user->linkedin || $user->website)
    <div class="flex gap-8 mb-16" style="flex-wrap: wrap;">
        @if($user->github)
            <a href="{{ $user->github }}" target="_blank" class="btn btn-ghost btn-sm" style="font-size: 13px;">
                <i class="ph ph-github-logo"></i> GitHub
            </a>
        @endif
        @if($user->linkedin)
            <a href="{{ $user->linkedin }}" target="_blank" class="btn btn-ghost btn-sm" style="font-size: 13px;">
                <i class="ph ph-linkedin-logo"></i> LinkedIn
            </a>
        @endif
        @if($user->website)
            <a href="{{ $user->website }}" target="_blank" class="btn btn-ghost btn-sm" style="font-size: 13px;">
                <i class="ph ph-globe"></i> Website
            </a>
        @endif
    </div>
    @endif

    <div class="stat-row mb-24">
        <div class="stat-item"><div class="stat-value">{{ $stats['posts'] }}</div><div class="stat-label">Posts</div></div>
        <div class="stat-item"><div class="stat-value">{{ $stats['comments'] }}</div><div class="stat-label">Replies</div></div>
        <div class="stat-item"><div class="stat-value">{{ $stats['followers'] }}</div><div class="stat-label">Followers</div></div>
        <div class="stat-item"><div class="stat-value">{{ $stats['following'] }}</div><div class="stat-label">Following</div></div>
    </div>

    <h2 style="font-size: 17px; font-weight: 600; margin-bottom: 16px;"><i class="ph ph-article"></i> Recent Posts</h2>
    <div class="card-flat" style="padding: 0; overflow: hidden;">
        @forelse($posts as $post)
            <div class="post-card">
                <div class="post-content">
                    <a href="{{ route('posts.show', $post->slug) }}" class="post-title">{{ $post->title }}</a>
                    <div class="tag-list mb-8">
                        @foreach($post->tags as $tag)
                            <a href="{{ route('search', ['tag' => $tag->slug]) }}" class="tag">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                    <div class="post-meta">
                        <span><i class="ph ph-arrow-fat-up"></i> {{ $post->votes_sum_value ?? 0 }}</span>
                        <span><i class="ph ph-chat-circle"></i> {{ $post->comments_count }}</span>
                        <span><i class="ph ph-folder"></i> {{ $post->forum->name }}</span>
                        <span>{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state" style="padding: 40px;"><h3>No posts yet</h3><p>This user hasn't created any posts.</p></div>
        @endforelse
    </div>
</x-app-layout>
