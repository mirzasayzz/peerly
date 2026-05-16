{{-- Top Navigation Bar --}}
<nav class="navbar">
    {{-- Left: Brand + Mobile Toggle --}}
    <div class="flex items-center gap-16">
        <button class="btn-icon btn-ghost hidden-desktop" onclick="toggleSidebar()" id="sidebar-toggle" style="display:none;">
            <i class="ph ph-list" style="font-size:22px;"></i>
        </button>
        <a href="{{ url('/') }}" class="navbar-brand">
            <span class="logo-icon">P</span>
            <span>Peerly</span>
        </a>
    </div>

    {{-- Center: Search --}}
    <div class="navbar-center">
        <form action="{{ route('search') }}" method="GET" class="search-bar">
            <i class="ph ph-magnifying-glass search-icon"></i>
            <input type="text" name="q" placeholder="Search discussions, topics, people..." value="{{ request('q') }}" id="global-search">
        </form>
    </div>

    {{-- Right: Actions --}}
    <div class="navbar-actions">
        <button class="btn-icon btn-ghost" onclick="toggleTheme()" title="Toggle theme" id="theme-toggle">
            <i class="ph ph-moon" style="font-size:20px;"></i>
        </button>

        @auth
            <a href="{{ route('posts.create') }}" class="btn btn-gradient btn-sm" id="new-post-btn">
                <i class="ph ph-plus"></i>
                New Post
            </a>

            <a href="{{ route('notifications.index') }}" class="btn-icon btn-ghost {{ auth()->user()->unreadNotifications->count() > 0 ? 'notif-dot' : '' }}" id="notif-btn">
                <i class="ph ph-bell" style="font-size:20px;"></i>
            </a>

            <div class="dropdown">
                <button class="btn-icon btn-ghost" onclick="toggleDropdown('user-menu')" id="user-menu-btn">
                    <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="avatar avatar-sm">
                </button>
                <div class="dropdown-menu" id="user-menu">
                    <div style="padding: 8px 12px; border-bottom: 1px solid var(--border); margin-bottom: 4px;">
                        <div class="font-semibold text-sm">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-muted">{{ '@' . auth()->user()->username }}</div>
                    </div>
                    <a href="{{ route('profile.show', auth()->user()->username ?? auth()->user()->id) }}" class="dropdown-item">
                        <i class="ph ph-user"></i> My Profile
                    </a>
                    <a href="{{ route('bookmarks.index') }}" class="dropdown-item">
                        <i class="ph ph-bookmark-simple"></i> Bookmarks
                    </a>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ph ph-gear"></i> Settings
                    </a>
                    @if(auth()->user()->isModerator())
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                        <i class="ph ph-shield-check"></i> Admin Panel
                    </a>
                    @endif
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item" style="color: var(--danger);">
                            <i class="ph ph-sign-out"></i> Sign Out
                        </button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Sign In</a>
            <a href="{{ route('register') }}" class="btn btn-gradient btn-sm">Join Peerly</a>
        @endauth
    </div>
</nav>

<style>
    @media (min-width: 1025px) { #sidebar-toggle { display: none !important; } }
    @media (max-width: 1024px) { #sidebar-toggle { display: flex !important; } }
</style>
