<x-app-layout>
    <div style="max-width: 720px;">
        <h1 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">
            <i class="ph ph-plus-circle" style="color: var(--accent);"></i> Create New Post
        </h1>

        <form method="POST" action="{{ route('posts.store') }}" class="card-flat" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label" for="title">Title</label>
                <input type="text" name="title" id="title" class="form-input" placeholder="What's on your mind?" value="{{ old('title') }}" required>
                @error('title') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="forum_id">Forum</label>
                <select name="forum_id" id="forum_id" class="form-select" required>
                    <option value="">Select a forum...</option>
                    @foreach($forums as $categoryName => $categoryForums)
                        <optgroup label="{{ $categoryName }}">
                            @foreach($categoryForums as $forum)
                                <option value="{{ $forum->id }}" {{ old('forum_id', request('forum')) == $forum->id ? 'selected' : '' }}>
                                    {{ $forum->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                @error('forum_id') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="body">Content</label>
                <textarea name="body" id="body" class="form-textarea" rows="10" placeholder="Share your thoughts, questions, or resources..." required>{{ old('body') }}</textarea>
                @error('body') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="image">Attach Image / File (optional)</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*,.pdf,.doc,.docx" style="padding-top: 8px;">
                <p class="text-xs text-muted" style="margin-top: 6px;">
                    Allowed formats: JPEG, PNG, JPG, GIF, SVG, WEBP, PDF, DOC, DOCX up to 3MB.
                </p>
                @error('image') <div class="form-error mt-4">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tags (select up to 5)</label>
                <div class="tag-list">
                    @foreach($tags as $tag)
                        <label style="cursor: pointer;">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="hidden" {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                                onchange="this.parentElement.querySelector('.tag').classList.toggle('active-tag')">
                            <span class="tag">{{ $tag->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('tags') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="flex justify-between items-center" style="padding-top: 16px; border-top: 1px solid var(--border); flex-wrap: wrap; gap: 12px;">
                <a href="{{ url()->previous() }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-gradient">
                    <i class="ph ph-paper-plane-tilt"></i> Publish Post
                </button>
            </div>
        </form>
    </div>

    <style>
        .tag:has(+ input:checked), input:checked + .tag, label:has(input:checked) .tag { background: var(--accent); color: white; }
    </style>
</x-app-layout>
