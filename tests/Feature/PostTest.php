<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @var string */
    const POST_INDEX_ROUTE_NAME = 'dashboard';

    /** @var string */
    const POST_SHOW_ROUTE_NAME = 'post.show';

    /** @var string */
    const POST_DELETE_ROUTE_NAME = 'post.destroy';

    /** @var string */
    const POST_TOGGLE_VISIBLE_ROUTE_NAME = 'post.toggleVisible';

    /** @var Post $myPost */
    protected $myPost;

    /** @var User $user */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $roleUser = Role::create(['name' => 'user']);
        $this->user->assignRole($roleUser);

        $this->myPost = Post::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_get_my_post()
    {
        // Given
        $this->be($this->user); // auth
        $body = ['id' => $this->myPost->id];

        // When
        $response = $this->get(route(self::POST_SHOW_ROUTE_NAME, $body));

        // Then
        $response->assertStatus(200);
    }

    public function test_get_foreign_post()
    {
        // Given
        $this->be($this->user); // auth
        $anotherUser = User::factory()->create();
        $anotherUserPost = Post::factory()->create(['user_id' => $anotherUser->id]);
        $body = ['id' => $anotherUserPost->id];

        // When
        $response = $this->get(route(self::POST_SHOW_ROUTE_NAME, $body));

        // Then
        $response->assertStatus(500);
    }

    public function test_get_foreign_post_by_admin()
    {
        // Given
        /** @var User $admin */
        $admin = User::factory()->create();
        $adminRole = Role::create(['name' => 'admin']);
        $admin->assignRole($adminRole);
        $this->be($admin); // auth
        $anotherUser = User::factory()->create();
        $anotherUserPost = Post::factory()->create(['user_id' => $anotherUser->id]);
        $body = ['id' => $anotherUserPost->id];

        // When
        $response = $this->get(route(self::POST_SHOW_ROUTE_NAME, $body));

        // Then
        $response->assertStatus(200);
    }

    public function test_delete_my_post()
    {
        // Given
        $this->be($this->user); // auth
        $body = ['id' => $this->myPost->id];

        // When
        $response = $this->delete(route(self::POST_DELETE_ROUTE_NAME, $body));

        // Then
        $response->assertStatus(302); // redirect
    }

    public function test_delete_foreign_post_by_admin()
    {
        // Given
        /** @var User $admin */
        $admin = User::factory()->create();
        $adminRole = Role::create(['name' => 'admin']);
        $admin->assignRole($adminRole);
        $this->be($admin); // auth
        $anotherUser = User::factory()->create();
        $anotherUserPost = Post::factory()->create(['user_id' => $anotherUser->id]);
        $body = ['id' => $anotherUserPost->id];

        // When
        $response = $this->delete(route(self::POST_DELETE_ROUTE_NAME, $body));

        // Then
        $response->assertStatus(302); // redirect
    }

    public function test_delete_foreign_post()
    {
        // Given
        $this->be($this->user); // auth
        $anotherUser = User::factory()->create();
        $anotherUserPost = Post::factory()->create(['user_id' => $anotherUser->id]);
        $body = ['id' => $anotherUserPost->id];

        // When
        $response = $this->delete(route(self::POST_DELETE_ROUTE_NAME, $body));

        // Then
        $response->assertStatus(500);
    }


    public function test_toggle_visible_foreign_post()
    {
        // Given
        $this->be($this->user); // auth
        $anotherUser = User::factory()->create();
        $anotherUserPost = Post::factory()->create(['user_id' => $anotherUser->id]);
        $body = ['id' => $anotherUserPost->id];

        // When
        $response = $this->get(route(self::POST_TOGGLE_VISIBLE_ROUTE_NAME, $body));

        // Then
        $response->assertStatus(500);
    }
}
