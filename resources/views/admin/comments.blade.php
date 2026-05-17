<x-admin-layout>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: 700;">
            <i class="ph ph-chats" style="color: var(--accent);"></i> Manage Comments
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
                        <th style="padding: 12px; color: var(--text-secondary); font-weight: 600;">Reply Snippet</th>
                        <th style="padding: 12px; color: var(--text-secondary); font-weight: 600;">Author</th>
                        <th style="padding: 12px; color: var(--text-secondary); font-weight: 600;">Date</th>
                        <th style="padding: 12px; color: var(--text-secondary); font-weight: 600; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comments as $c)
                        <tr style="border-bottom: 1px solid var(--border-light);">
                            <td style="padding: 12px; max-width: 400px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                @if($c->post)
                                    <a href="{{ route('posts.show', $c->post->slug) }}#comment-{{ $c->id }}" target="_blank" style="color: var(--text-primary);">{{ Str::limit(strip_tags($c->body), 60) }}</a>
                                @else
                                    <span style="color: var(--text-tertiary);">Orphaned Reply (Post deleted)</span>
                                @endif
                            </td>
                            <td style="padding: 12px; color: var(--text-tertiary);">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    @if($c->user)
                                        <img src="{{ $c->user->avatar_url }}" alt="" class="avatar avatar-sm">
                                        {{ $c->user->name }}
                                    @else
                                        Deleted User
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 12px; color: var(--text-tertiary);">{{ $c->created_at->format('M d, Y') }}</td>
                            <td style="padding: 12px; text-align: right;">
                                <form action="{{ route('admin.comments.destroy', $c) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this reply?');">
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
            {{ $comments->links() }}
        </div>
    </div>
</x-admin-layout>
