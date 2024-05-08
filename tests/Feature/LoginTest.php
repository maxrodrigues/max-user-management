<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_user_can_login(): void
    {
        $user = $this->createUser();
        $response = $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = $this->createUser();
        $response = $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    private function createUser(): User
    {
        return User::factory()->create();
    }
}
