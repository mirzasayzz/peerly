{{-- Recursive Comment Component --}}
<div class="comment-item {{ $comment->is_solution ? 'comment-solution' : '' }}" id="comment-{{ $comment->id }}">
    <a href="{{ route('profile.show', $comment->user->username ?? $comment->user->id) }}">
        <img src="{{ $comment->user->avatar_url }}" alt="" class="avatar avatar-sm">
    </a>
    <div class="comment-body">
        <div class="flex items-center gap-8">
            <a href="{{ route('profile.show', $comment->user->username ?? $comment->user->id) }}" class="badge-name text-sm">{{ $comment->user->name }}</a>
            @if($comment->user->isAdmin())
                <span class="tag" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); font-size: 10px; padding: 2px 6px; line-height: 1; display: inline-flex; align-items: center; gap: 2px;">
                    <i class="ph ph-shield" style="font-size: 10px;"></i> Admin
                </span>
            @endif
            @if($comment->is_solution)
                <span class="tag" style="background: var(--success-soft); color: var(--success); font-size: 11px;">
                    <i class="ph-fill ph-check-circle"></i> Solution
                </span>
            @endif
            @if($comment->user_id === $post->user_id)
                <span class="tag" style="font-size: 11px;">OP</span>
            @endif
            <span class="text-xs text-muted">{{ $comment->created_at->diffForHumans() }}</span>
        </div>
        <div class="comment-text mt-4">
            @if($comment->image_url)
                @php
                    $ext = strtolower(pathinfo($comment->image_path, PATHINFO_EXTENSION));
                    $isDoc = in_array($ext, ['pdf', 'doc', 'docx']);
                @endphp
                @if($isDoc)
                    <div style="margin-bottom: 12px; padding: 12px; border-radius: var(--radius-sm); background: var(--bg-tertiary); border: 1px solid var(--border); display: inline-flex; align-items: center; gap: 8px; max-width: 100%;">
                        <i class="ph ph-file-text" style="font-size: 24px; color: var(--accent);"></i>
                        <div>
                            <a href="{{ $comment->image_url }}" target="_blank" style="font-size: 13px; font-weight: 600; color: var(--accent); display: inline-flex; align-items: center; gap: 4px;"><i class="ph ph-download-simple"></i> Download PDF/Document</a>
                        </div>
                    </div>
                @else
                    <div style="margin-bottom: 12px; max-width: 300px; border-radius: var(--radius-sm); overflow: hidden; border: 1px solid var(--border);">
                        <img src="{{ $comment->image_url }}" alt="Attachment" style="max-width: 100%; height: auto; display: block;">
                    </div>
                @endif
            @endif
            {!! nl2br(e($comment->body)) !!}
        </div>
        <div class="comment-actions">
            @auth
            @if(!$post->is_locked)
                <button onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('hidden');">
                    <i class="ph ph-arrow-bend-up-left"></i> Reply
                </button>
            @endif
            @if(auth()->id() === $post->user_id && !$comment->is_solution)
                <form method="POST" action="{{ route('comments.solution', $comment) }}" style="display:inline;">
                    @csrf
                    <button type="submit"><i class="ph ph-check-circle"></i> Mark Solution</button>
                </form>
            @endif
            @if(auth()->id() === $comment->user_id || auth()->user()->isModerator())
                <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('Delete?');" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" style="color: var(--danger);"><i class="ph ph-trash"></i> Delete</button>
                </form>
            @endif
            @endauth
        </div>

        {{-- Inline Reply Form --}}
        @auth
        <form id="reply-form-{{ $comment->id }}" class="hidden mt-8" method="POST" action="{{ route('comments.store', $post) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <textarea name="body" class="form-textarea" rows="2" placeholder="Reply to {{ $comment->user->name }}..." required></textarea>
            
            <div class="flex justify-between items-center mt-4" style="flex-wrap: wrap; gap: 8px;">
                <div style="display: flex; flex-direction: column; gap: 2px;">
                    <input type="file" name="image" class="text-xs text-muted" accept="image/*,.pdf,.doc,.docx" style="max-width: 200px;">
                    <span class="text-xs text-muted" style="font-size: 10px;">Images or PDF/Docs up to 3MB</span>
                </div>
                <button type="submit" class="btn btn-primary btn-sm"><i class="ph ph-paper-plane-tilt"></i> Reply</button>
            </div>
        </form>
        @endauth

        {{-- Nested Replies --}}
        @if($comment->replies->count() && $comment->depth < 3)
            <div class="comment-replies">
                @foreach($comment->replies as $reply)
                    @include('posts._comment', ['comment' => $reply, 'post' => $post])
                @endforeach
            </div>
        @endif
    </div>
</div>
