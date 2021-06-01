<?php

namespace Tests\Feature\Auth;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_register_form()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs("auth.register");
    }

    public function test_valid_user_can_register()
    {
        $user = factory(User::class)->make();
        $data = [
            "username" => $user->username,
            "email" => $user->email,
            "password" => "test"
        ];

        $response = $this->post("/register", $data);

        $response->assertStatus(302);
        $this->assertAuthenticated();
    }

    public function test_invalid_user_cannot_register()
    {
        $user = factory(User::class)->make();
        $data = [
            "username" => $user->username,
            "email" => $user->email,
            "password" => "t"
        ];

        $response = $this->post("/register", $data);

        $response->assertSessionHasErrors(["password" => "The password must be at least 4 characters."]);
        $this->assertGuest();
    }
}
