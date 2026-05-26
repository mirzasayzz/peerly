<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Forum;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttachmentUploadTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Forum $forum;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['username' => 'test_uploader']);

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

    public function test_post_creation_allows_pdf_under_3mb(): void
    {
        Storage::fake();

        // Create a 1MB mock PDF file
        $file = UploadedFile::fake()->create('document.pdf', 1024); // 1024 KB = 1 MB

        $response = $this->actingAs($this->user)
            ->post(route('posts.store'), [
                'title' => 'Test Post Title',
                'body' => 'This is a long body content of at least ten characters.',
                'forum_id' => $this->forum->id,
                'image' => $file,
            ]);

        $post = Post::where('title', 'Test Post Title')->first();
        $this->assertNotNull($post);
        $response->assertRedirect(route('posts.show', $post->slug));
        
        $this->assertNotNull($post->image_path);
        Storage::disk(config('filesystems.default'))->assertExists($post->image_path);
    }

    public function test_post_creation_rejects_file_over_3mb(): void
    {
        Storage::fake();

        // Create a 4MB mock PDF file (4096 KB > 3072 KB)
        $file = UploadedFile::fake()->create('huge_document.pdf', 4096);

        $response = $this->actingAs($this->user)
            ->from(route('posts.create'))
            ->post(route('posts.store'), [
                'title' => 'Another Post Title',
                'body' => 'This is another long body content.',
                'forum_id' => $this->forum->id,
                'image' => $file,
            ]);

        $response->assertRedirect(route('posts.create'));
        $response->assertSessionHasErrors('image');
        $this->assertNull(Post::where('title', 'Another Post Title')->first());
    }

    public function test_comment_creation_allows_pdf_under_3mb(): void
    {
        Storage::fake();

        $post = Post::create([
            'user_id' => $this->user->id,
            'forum_id' => $this->forum->id,
            'title' => 'Sample Post',
            'slug' => 'sample-post',
            'body' => 'Post body goes here.',
        ]);

        $file = UploadedFile::fake()->create('comment_document.pdf', 1024);

        $response = $this->actingAs($this->user)
            ->post(route('comments.store', $post), [
                'body' => 'This is a valid reply comment.',
                'image' => $file,
            ]);

        $response->assertRedirect();
        $comment = $post->comments()->first();
        $this->assertNotNull($comment);
        $this->assertNotNull($comment->image_path);
        Storage::disk(config('filesystems.default'))->assertExists($comment->image_path);
    }

    public function test_comment_creation_rejects_file_over_3mb(): void
    {
        Storage::fake();

        $post = Post::create([
            'user_id' => $this->user->id,
            'forum_id' => $this->forum->id,
            'title' => 'Sample Post',
            'slug' => 'sample-post',
            'body' => 'Post body goes here.',
        ]);

        $file = UploadedFile::fake()->create('huge_comment_document.pdf', 4096);

        $response = $this->actingAs($this->user)
            ->post(route('comments.store', $post), [
                'body' => 'This is a valid reply comment.',
                'image' => $file,
            ]);

        $response->assertSessionHasErrors('image');
        $this->assertEquals(0, $post->comments()->count());
    }
}
