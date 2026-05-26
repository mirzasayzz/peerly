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

            <div class="form-group">
                <label class="form-label" for="image">Post Image (optional)</label>

                @if(!empty($post->image_path))
                    <div style="margin-bottom: 12px; border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 16px; background: rgba(255,255,255,0.02); display: flex; flex-direction: column; gap: 12px; max-width: 100%;">
                        <span class="text-sm text-muted">Current Attachment:</span>
                        <img
                            src="{{ $post->image_url }}"
                            alt="Post image"
                            style="max-width: 100%; border-radius: var(--radius-md); border: 1px solid var(--border); max-height: 250px; object-fit: cover;"
                        >
                        <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; user-select: none;">
                            <input
                                type="checkbox"
                                name="remove_image"
                                id="remove_image"
                                value="1"
                                style="cursor: pointer; width: 16px; height: 16px; accent-color: var(--accent);"
                            >
                            <span class="text-sm text-muted">Remove current image attachment</span>
                        </label>
                    </div>
                @endif

                <input
                    type="file"
                    name="image"
                    id="image"
                    class="form-control"
                    accept="image/*"
                    style="padding-top: 8px; width: 100%;"
                >
                <p class="text-xs text-muted" style="margin-top: 6px;">
                    Upload a new image to replace the current one, or attach an image if none exists (JPEG, PNG, JPG, GIF, SVG, WEBP up to 5MB).
                </p>

                @error('image')
                    <div class="form-error mt-2">{{ $message }}</div>
                @enderror
            </div>

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
