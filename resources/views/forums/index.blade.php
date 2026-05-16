<x-app-layout>
    <h1 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">
        <i class="ph ph-chats-circle" style="color: var(--accent);"></i> Forums
    </h1>

    @forelse($categories as $category)
        <div class="mb-24">
            <div class="flex items-center gap-8 mb-16">
                <span style="width: 28px; height: 28px; border-radius: var(--radius-sm); background: {{ $category->color }}20; color: {{ $category->color }}; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                    <i class="ph ph-{{ $category->icon ?? 'folder' }}"></i>
                </span>
                <h2 style="font-size: 16px; font-weight: 600;">{{ $category->name }}</h2>
            </div>

            <div style="display: grid; gap: 10px;">
                @foreach($category->forums as $forum)
                    <a href="{{ route('forums.show', $forum->slug) }}" class="category-card">
                        <div class="category-icon" style="background: {{ $category->color }}15; color: {{ $category->color }};">
                            <i class="ph ph-{{ $forum->icon ?? 'chat-circle-dots' }}"></i>
                        </div>
                        <div class="category-info" style="flex: 1;">
                            <h3>{{ $forum->name }}</h3>
                            <p>{{ Str::limit($forum->description, 80) }}</p>
                        </div>
                        <div style="text-align: right; min-width: 80px;">
                            <div class="font-semibold" style="font-size: 15px;">{{ $forum->posts_count }}</div>
                            <div class="text-xs text-muted">discussions</div>
                        </div>
                        @if($forum->latestPost)
                        <div style="text-align: right; min-width: 140px; font-size: 12px; color: var(--text-tertiary);">
                            <div class="truncate">{{ Str::limit($forum->latestPost->title, 30) }}</div>
                            <div>by {{ $forum->latestPost->user->name }} · {{ $forum->latestPost->created_at->diffForHumans() }}</div>
                        </div>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    @empty
        <div class="empty-state">
            <div class="empty-icon"><i class="ph ph-folder-open"></i></div>
            <h3>No forums yet</h3>
            <p>Forums will appear here once created by admins.</p>
        </div>
    @endforelse
</x-app-layout>
