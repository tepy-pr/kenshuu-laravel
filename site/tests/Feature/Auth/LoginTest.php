<?php

namespace Tests\Feature\Auth;

use App\Http\Controllers\Auth\LoginController;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_login_form()
    {
        $response = $this->get('/login');

        $response->assertViewIs("auth.login");
        $response->assertStatus(200);
    }

    public function test_valid_user_can_login()
    {

        $user = factory(User::class)->create();
        $data = [
            "email" => $user->email,
            "password" => "password"
        ];

        $response = $this->post("/login", $data);

        $response->assertStatus(302);
        $response->assertRedirect("/");
        $this->assertAuthenticatedas($user);
    }

    public function test_invalid_user_cannot_login()
    {
        $user = factory(User::class)->create();
        $data = [
            "email" => $user->email,
            "password" => "invalid"
        ];

        $response = $this->post("/login", $data);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_user_can_logout()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/logout');

        $response->assertStatus(302);
        $response->assertRedirect("/");
        $this->assertGuest();
    }
}
