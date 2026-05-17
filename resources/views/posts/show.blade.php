<x-app-layout>
    <div class="breadcrumb">
        <a href="{{ url('/') }}"><i class="ph ph-house"></i></a>
        <span class="sep">/</span>
        <a href="{{ route('forums.show', $post->forum->slug) }}">{{ $post->forum->name }}</a>
        <span class="sep">/</span>
        <span style="color: var(--text-primary);">{{ Str::limit($post->title, 40) }}</span>
    </div>

    <div class="flex gap-24 show-layout" style="align-items: flex-start;">
        {{-- Vote Sidebar (Desktop/Mobile row) --}}
        <div class="vote-col" data-voteable="post-{{ $post->id }}" style="position: sticky; top: 88px; padding-top: 8px;">
            <button class="vote-btn vote-up {{ $post->user_vote === 1 ? 'active-up' : '' }}" onclick="vote('post', {{ $post->id }}, 1)">
                <i class="ph-bold ph-arrow-fat-up" style="font-size: 20px;"></i>
            </button>
            <span class="vote-count" style="font-size: 18px;">{{ $post->votes_sum_value ?? 0 }}</span>
            <button class="vote-btn vote-down {{ $post->user_vote === -1 ? 'active-down' : '' }}" onclick="vote('post', {{ $post->id }}, -1)">
                <i class="ph-bold ph-arrow-fat-down" style="font-size: 20px;"></i>
            </button>
            @auth
            <div style="margin-top: 12px; border-top: 1px solid var(--border); padding-top: 12px;">
                <button class="vote-btn {{ auth()->user()->hasBookmarked($post) ? 'text-accent' : '' }}" onclick="toggleBookmark({{ $post->id }}, this)" title="Bookmark">
                    <i class="ph ph-bookmark-simple" style="font-size: 18px;"></i>
                </button>
            </div>
            @endauth
        </div>

        {{-- Main Post Content --}}
        <div style="flex: 1; min-width: 0;">
            {{-- Post Header --}}
            <div class="card-flat mb-24">
                <div class="flex items-center gap-8 mb-16" style="flex-wrap: wrap;">
                    @if($post->is_pinned)
                        <span class="tag" style="background: var(--warning-soft); color: var(--warning);"><i class="ph ph-push-pin"></i> Pinned</span>
                    @endif
                    @if($post->is_resolved)
                        <span class="tag" style="background: var(--success-soft); color: var(--success);"><i class="ph ph-check-circle"></i> Resolved</span>
                    @endif
                    @if($post->is_locked)
                        <span class="tag" style="background: var(--danger-soft); color: var(--danger);"><i class="ph ph-lock"></i> Locked</span>
                    @endif
                </div>

                <h1 style="font-size: 24px; font-weight: 700; line-height: 1.4; margin-bottom: 16px;">{{ $post->title }}</h1>

                <div class="flex items-center gap-12 mb-16">
                    <a href="{{ route('profile.show', $post->user->username ?? $post->user->id) }}" class="user-badge">
                        <img src="{{ $post->user->avatar_url }}" alt="" class="avatar">
                        <div>
                            <span class="badge-name">{{ $post->user->name }}</span>
                            <div class="text-xs text-muted">{{ $post->created_at->diffForHumans() }} · {{ $post->view_count }} views</div>
                        </div>
                    </a>
                    <span class="reputation-badge" style="color: {{ $post->user->reputation_badge['color'] }};">
                        {{ $post->user->reputation_badge['icon'] }} {{ $post->user->reputation_badge['label'] }}
                    </span>
                </div>

                <div class="tag-list mb-16">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('search', ['tag' => $tag->slug]) }}" class="tag">{{ $tag->name }}</a>
                    @endforeach
                </div>

                {{-- Post Body --}}
                <div style="font-size: 15px; line-height: 1.8; color: var(--text-primary);">
                    @if($post->image_url)
                        @php
                            $ext = strtolower(pathinfo($post->image_path, PATHINFO_EXTENSION));
                            $isDoc = in_array($ext, ['pdf', 'doc', 'docx']);
                        @endphp
                        
                        @if($isDoc)
                            <div style="margin-bottom: 24px; padding: 16px; border-radius: var(--radius-md); background: var(--bg-tertiary); border: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
                                <i class="ph ph-file-text" style="font-size: 32px; color: var(--accent);"></i>
                                <div>
                                    <div style="font-weight: 600; color: var(--text-primary);">Attached Document</div>
                                    <a href="{{ $post->image_url }}" target="_blank" style="font-size: 14px; color: var(--accent); display: inline-flex; align-items: center; gap: 4px;"><i class="ph ph-download-simple"></i> Download File</a>
                                </div>
                            </div>
                        @else
                            <div style="margin-bottom: 24px; border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border);">
                                <img src="{{ $post->image_url }}" alt="Attachment" style="max-width: 100%; height: auto; display: block;">
                            </div>
                        @endif
                    @endif
                    {!! nl2br(e($post->body)) !!}
                </div>

                {{-- Post Actions --}}
                @auth
                @if(auth()->id() === $post->user_id)
                <div class="flex gap-8 mt-16" style="padding-top: 16px; border-top: 1px solid var(--border);">
                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-ghost btn-sm"><i class="ph ph-pencil"></i> Edit</a>
                    <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Delete this post?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-ghost btn-sm" style="color: var(--danger);"><i class="ph ph-trash"></i> Delete</button>
                    </form>
                </div>
                @endif
                @endauth
            </div>

            {{-- Comments Section --}}
            <div class="card-flat">
                <h2 style="font-size: 17px; font-weight: 600; margin-bottom: 20px;">
                    <i class="ph ph-chat-circle"></i> {{ $post->comments_count }} {{ Str::plural('Reply', $post->comments_count) }}
                </h2>

                {{-- Comment Form --}}
                @auth
                @if(!$post->is_locked)
                <form method="POST" action="{{ route('comments.store', $post) }}" style="margin-bottom: 24px;" enctype="multipart/form-data">
                    @csrf
                    <textarea name="body" class="form-textarea" placeholder="Write your reply..." rows="3" required>{{ old('body') }}</textarea>
                    @error('body') <div class="form-error">{{ $message }}</div> @enderror
                    
                    <div class="flex justify-between items-center mt-8">
                        <div>
                            <input type="file" name="image" id="image" class="text-sm text-muted" accept="image/*">
                            @error('image') <div class="form-error mt-4">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="ph ph-paper-plane-tilt"></i> Post Reply</button>
                    </div>
                </form>
                @endif
                @endauth

                {{-- Comment Thread --}}
                <div class="comment-thread">
                    @foreach($post->rootComments as $comment)
                        @include('posts._comment', ['comment' => $comment, 'post' => $post])
                    @endforeach
                </div>

                @if($post->rootComments->isEmpty())
                <div class="empty-state" style="padding: 40px 20px;">
                    <div class="empty-icon"><i class="ph ph-chat-dots"></i></div>
                    <h3>No replies yet</h3>
                    <p>Be the first to share your thoughts!</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
