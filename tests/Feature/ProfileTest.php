<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_only_registered_users_can_view_their_profile(): void
    {
        $response = $this->get(route('profile'));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $user = $this->createUser();
        $this->actingAs($user);

        $authResponse = $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->json(
            'GET',
            route('profile'),
            ['email' => $user->email, 'password' => 'password'],
            ['HTTP_Authorization' => 'Bearer ' . $authResponse->json('access_token')]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertExactJson(['profile' => $user->toArray()]);

    }

    private function createUser(): User
    {
        return User::factory()->create();
    }
}
