<x-app-layout>
    <div style="max-width: 720px;">
        <h1 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">
            <i class="ph ph-pencil-simple-line" style="color: var(--accent);"></i>
            Edit Post
        </h1>

        <form method="POST" action="{{ route('posts.update', $post) }}" class="card-flat" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="title">Title</label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    class="form-input"
                    placeholder="What's on your mind?"
                    value="{{ old('title', $post->title) }}"
                    required
                >

                @error('title')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="forum_id">Forum</label>

                <select name="forum_id" id="forum_id" class="form-select" required>
                    <option value="">Select a forum...</option>

                    @foreach($forums as $categoryName => $categoryForums)
                        <optgroup label="{{ $categoryName }}">
                            @foreach($categoryForums as $forum)
                                <option
                                    value="{{ $forum->id }}"
                                    {{ old('forum_id', $post->forum_id) == $forum->id ? 'selected' : '' }}
                                >
                                    {{ $forum->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>

                @error('forum_id')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="body">Content</label>

                <textarea
                    name="body"
                    id="body"
                    class="form-textarea"
                    rows="10"
                    placeholder="Share your thoughts, questions, or resources..."
                    required
                >{{ old('body', $post->body) }}</textarea>

                @error('body')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            @if(!empty($post->image_path))
                <div class="form-group">
                    <label class="form-label">Current Image</label>

                    <img
                        src="{{ $post->image_url }}"
                        alt="Post image"
                        style="width: 100%; border-radius: var(--radius-lg); border: 1px solid var(--border); max-height: 420px; object-fit: cover;"
                    >
                </div>
            @endif

            <div class="form-group">
                <label class="form-label">Tags (select up to 5)</label>

                <div class="tag-list">
                    @foreach($tags as $tag)
                        <label style="cursor: pointer;">
                            <input
                                type="checkbox"
                                name="tags[]"
                                value="{{ $tag->id }}"
                                class="hidden"
                                {{ in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())) ? 'checked' : '' }}
                                onchange="this.parentElement.querySelector('.tag').classList.toggle('active-tag')"
                            >

                            <span class="tag {{ in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())) ? 'active-tag' : '' }}">
                                {{ $tag->name }}
                            </span>
                        </label>
                    @endforeach
                </div>

                @error('tags')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div
                class="flex justify-between items-center"
                style="padding-top: 16px; border-top: 1px solid var(--border); flex-wrap: wrap; gap: 12px;"
            >
                <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-ghost">
                    Cancel
                </a>

                <button type="submit" class="btn btn-gradient">
                    <i class="ph ph-floppy-disk"></i>
                    Update Post
                </button>
            </div>
        </form>
    </div>

    <style>
        .active-tag {
            background: var(--accent) !important;
            color: white !important;
        }

        label:has(input:checked) .tag {
            background: var(--accent);
            color: white;
        }
    </style>
</x-app-layout>
