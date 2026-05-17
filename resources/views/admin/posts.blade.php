<x-admin-layout>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: 700;">
            <i class="ph ph-article" style="color: var(--accent);"></i> Manage Posts
        </h1>
    </div>

    @if(session('status'))
        <div style="padding: 12px; background: var(--success-soft); color: var(--success); border-radius: 8px; margin-bottom: 24px;">
            {{ session('status') }}
        </div>
    @endif

    <div class="card">
        <div style="overflow-x: auto;">
            <table style="width: 100%; text-align: left; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <th style="padding: 12px; color: var(--text-secondary); font-weight: 600;">Title</th>
                        <th style="padding: 12px; color: var(--text-secondary); font-weight: 600;">Author</th>
                        <th style="padding: 12px; color: var(--text-secondary); font-weight: 600;">Date</th>
                        <th style="padding: 12px; color: var(--text-secondary); font-weight: 600; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $p)
                        <tr style="border-bottom: 1px solid var(--border-light);">
                            <td style="padding: 12px; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                @if($p->is_pinned)
                                    <i class="ph ph-push-pin" style="color: var(--warning); margin-right: 4px;"></i>
                                @endif
                                <a href="{{ route('posts.show', $p->slug) }}" target="_blank" style="color: var(--text-primary); font-weight: 500;">{{ $p->title }}</a>
                            </td>
                            <td style="padding: 12px; color: var(--text-tertiary);">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    @if($p->user)
                                        <img src="{{ $p->user->avatar_url }}" alt="" class="avatar avatar-sm">
                                        {{ $p->user->name }}
                                    @else
                                        Deleted User
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 12px; color: var(--text-tertiary);">{{ $p->created_at->format('M d, Y') }}</td>
                            <td style="padding: 12px; text-align: right; display: flex; justify-content: flex-end; gap: 8px;">
                                <form action="{{ route('admin.posts.pin', $p) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm" style="color: {{ $p->is_pinned ? 'var(--warning)' : 'var(--text-secondary)' }}; background: transparent; border: 1px solid var(--border); padding: 4px 10px;">
                                        <i class="ph ph-push-pin{{ $p->is_pinned ? '-slash' : '' }}"></i> {{ $p->is_pinned ? 'Unpin' : 'Pin' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.posts.destroy', $p) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post completely?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="color: var(--danger); background: transparent; border: 1px solid var(--danger); padding: 4px 10px;">
                                        <i class="ph ph-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-16">
            {{ $posts->links() }}
        </div>
    </div>
</x-admin-layout>
