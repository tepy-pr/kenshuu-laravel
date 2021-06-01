<?php

namespace Tests\Feature\Auth;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    protected function tearDown(): void
    {
        $this->user->delete();

        parent::tearDown();
    }

    public function test_user_can_see_login_form()
    {
        $response = $this->get('/login');

        $response->assertViewIs("auth.login");
        $response->assertStatus(200);
    }

    public function test_valid_user_can_login()
    {

        $data = [
            "email" => $this->user->email,
            "password" => "password"
        ];

        $response = $this->post("/login", $data);

        $response->assertStatus(302);
        $response->assertRedirect("/");
        $this->assertAuthenticatedas($this->user);
    }

    public function test_invalid_user_cannot_login()
    {
        $data = [
            "email" => $this->user->email,
            "password" => "invalid"
        ];

        $response = $this->post("/login", $data);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_user_can_logout()
    {

        $response = $this->actingAs($this->user)->get('/logout');

        $response->assertStatus(302);
        $response->assertRedirect("/");
        $this->assertGuest();
    }
}
