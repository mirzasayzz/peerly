<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Forum;
use App\Models\Post;
use App\Models\User;
use App\Models\Vote;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $otherUser;
    protected User $moderator;
    protected Forum $forum;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['username' => 'student_user']);
        $this->otherUser = User::factory()->create(['username' => 'other_student_user']);
        $this->moderator = User::factory()->create(['role' => 'moderator', 'username' => 'mod_user']);

        $category = Category::create([
            'name' => 'Academics',
            'slug' => 'academics',
            'icon' => 'graduation-cap',
            'color' => '#7c5cfc',
            'sort_order' => 1
        ]);

        $this->forum = Forum::create([
            'category_id' => $category->id,
            'name' => 'General Chat',
            'slug' => 'general-chat',
            'icon' => 'chat',
            'description' => 'General discussion'
        ]);
    }

    public function test_user_can_delete_own_post_if_not_resolved(): void
    {
        Storage::fake();

        $post = Post::create([
            'user_id' => $this->user->id,
            'forum_id' => $this->forum->id,
            'title' => 'My Unresolved Post',
            'slug' => 'my-unresolved-post',
            'body' => 'This is the body of my unresolved post.',
            'is_resolved' => false,
            'image_path' => 'posts/test_image.png',
        ]);

        // Add a comment, a vote, and a report
        $comment = Comment::create([
            'user_id' => $this->otherUser->id,
            'post_id' => $post->id,
            'body' => 'I have a comment on your post',
            'image_path' => 'comments/test_comment_image.png',
        ]);

        $post->votes()->create([
            'user_id' => $this->otherUser->id,
            'value' => 1,
        ]);

        $post->reports()->create([
            'user_id' => $this->otherUser->id,
            'reason' => 'Spam',
        ]);

        $comment->votes()->create([
            'user_id' => $this->user->id,
            'value' => -1,
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('posts.destroy', $post));

        $response->assertRedirect(route('forums.show', $this->forum->slug));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);

        // Verify votes and reports are deleted
        $this->assertDatabaseMissing('votes', ['voteable_id' => $post->id, 'voteable_type' => Post::class]);
        $this->assertDatabaseMissing('votes', ['voteable_id' => $comment->id, 'voteable_type' => Comment::class]);
        $this->assertDatabaseMissing('reports', ['reportable_id' => $post->id, 'reportable_type' => Post::class]);
    }

    public function test_user_cannot_delete_own_post_if_resolved(): void
    {
        $post = Post::create([
            'user_id' => $this->user->id,
            'forum_id' => $this->forum->id,
            'title' => 'My Resolved Post',
            'slug' => 'my-resolved-post',
            'body' => 'This is the body of my resolved post.',
            'is_resolved' => true,
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('posts.destroy', $post));

        $response->assertStatus(403);
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_moderator_can_delete_any_post_including_resolved(): void
    {
        $post = Post::create([
            'user_id' => $this->user->id,
            'forum_id' => $this->forum->id,
            'title' => 'Resolved Post',
            'slug' => 'resolved-post',
            'body' => 'This is the body of resolved post.',
            'is_resolved' => true,
        ]);

        $response = $this->actingAs($this->moderator)
            ->delete(route('posts.destroy', $post));

        $response->assertRedirect(route('forums.show', $this->forum->slug));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_user_cannot_delete_other_user_post(): void
    {
        $post = Post::create([
            'user_id' => $this->user->id,
            'forum_id' => $this->forum->id,
            'title' => 'Other Post',
            'slug' => 'other-post',
            'body' => 'This is the body of other post.',
            'is_resolved' => false,
        ]);

        $response = $this->actingAs($this->otherUser)
            ->delete(route('posts.destroy', $post));

        $response->assertStatus(403);
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_deleting_user_deletes_avatar_and_posts_cascades(): void
    {
        Storage::fake();

        $user = User::factory()->create([
            'username' => 'to_be_deleted',
            'avatar' => 'avatars/avatar.png',
        ]);

        $post = Post::create([
            'user_id' => $user->id,
            'forum_id' => $this->forum->id,
            'title' => 'User Post',
            'slug' => 'user-post',
            'body' => 'Post body goes here.',
            'image_path' => 'posts/post_attachment.pdf',
        ]);

        // Trigger user delete
        $user->delete();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        
        // Verify files are deleted from storage
        Storage::disk(config('filesystems.default'))->assertMissing('avatars/avatar.png');
        Storage::disk(config('filesystems.default'))->assertMissing('posts/post_attachment.pdf');
    }
}
