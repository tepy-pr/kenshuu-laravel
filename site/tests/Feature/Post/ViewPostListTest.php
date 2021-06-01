<?php

namespace Tests\Feature\Post;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewPostListTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauth_user_can_see_post_list()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs("posts.index")->assertSee("index");
    }

    public function test_auth_user_can_see_post_list()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertViewIs("posts.index")->assertSee("index");
    }
}
