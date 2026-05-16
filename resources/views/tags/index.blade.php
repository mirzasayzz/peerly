<x-app-layout>
    <h1 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;"><i class="ph ph-tag" style="color: var(--accent);"></i> Tags</h1>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px;">
        @foreach($tags as $tag)
            <a href="{{ route('search', ['tag' => $tag->slug]) }}" class="card" style="display: flex; align-items: center; justify-content: space-between;">
                <span class="tag">{{ $tag->name }}</span>
                <span class="text-xs text-muted">{{ $tag->posts_count }} posts</span>
            </a>
        @endforeach
    </div>
</x-app-layout>
