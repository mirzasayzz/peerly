{{-- Left Sidebar --}}
<aside class="app-sidebar">
    {{-- Main Navigation --}}
    <div class="sidebar-section">
        <div class="sidebar-title">Navigate</div>
        <a href="{{ url('/') }}" class="sidebar-link {{ request()->is('/') ? 'active' : '' }}">
            <span class="link-icon"><i class="ph ph-house"></i></span>
            Home
        </a>
        <a href="{{ route('forums.index') }}" class="sidebar-link {{ request()->is('forums*') ? 'active' : '' }}">
            <span class="link-icon"><i class="ph ph-chats-circle"></i></span>
            Forums
        </a>
        <a href="{{ route('search') }}" class="sidebar-link {{ request()->is('search*') ? 'active' : '' }}">
            <span class="link-icon"><i class="ph ph-magnifying-glass"></i></span>
            Explore
        </a>
        <a href="{{ route('tags.index') }}" class="sidebar-link {{ request()->is('tags*') ? 'active' : '' }}">
            <span class="link-icon"><i class="ph ph-tag"></i></span>
            Tags
        </a>
    </div>

    {{-- Categories --}}
    @if(isset($sidebarCategories) && $sidebarCategories->count())
    <div class="sidebar-section">
        <div class="sidebar-title">Categories</div>
        @foreach($sidebarCategories as $cat)
            <a href="{{ route('forums.index', ['category' => $cat->slug]) }}" class="sidebar-link">
                <span class="link-icon" style="color: {{ $cat->color }};">
                    <i class="ph ph-{{ $cat->icon ?? 'folder' }}"></i>
                </span>
                {{ $cat->name }}
                <span class="link-count">{{ $cat->forums_count ?? 0 }}</span>
            </a>
        @endforeach
    </div>
    @endif

    {{-- Popular Tags --}}
    @if(isset($sidebarTags) && $sidebarTags->count())
    <div class="sidebar-section">
        <div class="sidebar-title">Popular Tags</div>
        <div class="tag-list" style="padding: 0 12px;">
            @foreach($sidebarTags as $tag)
                <a href="{{ route('search', ['tag' => $tag->slug]) }}" class="tag">{{ $tag->name }}</a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Quick Stats --}}
    <div class="sidebar-section" style="margin-top: auto; padding-top: 20px; border-top: 1px solid var(--border);">
        <div class="sidebar-title">Community</div>
        <div style="padding: 0 12px; font-size: 13px; color: var(--text-tertiary); line-height: 2;">
            <div class="flex justify-between">
                <span><i class="ph ph-users"></i> Members</span>
                <span class="font-semibold" style="color: var(--text-secondary);">{{ \App\Models\User::count() }}</span>
            </div>
            <div class="flex justify-between">
                <span><i class="ph ph-chat-text"></i> Discussions</span>
                <span class="font-semibold" style="color: var(--text-secondary);">{{ \App\Models\Post::count() }}</span>
            </div>
        </div>
    </div>
</aside>
