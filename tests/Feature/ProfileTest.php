<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
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
        $response = $this->get(route('profile.show'));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $user = $this->createUser();
        $this->actingAs($user);

        $authResponse = $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->json(
            'GET',
            route('profile.show'),
            ['email' => $user->email, 'password' => 'password'],
            ['HTTP_Authorization' => 'Bearer ' . $authResponse->json('access_token')]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertExactJson(['profile' => $user->toArray()]);

    }

    public function test_only_registered_users_can_update_their_profile(): void
    {
        $userAndToken = $this->userAndToken();

        $response = $this->json(
            'POST',
            route('profile.update'),
            ['email' => $userAndToken['user'], 'password' => 'changePassword'],
            ['HTTP_Authorization' => 'Bearer ' . $userAndToken['token']]
        );

        $response->assertStatus(Response::HTTP_OK);

        $user = User::where('email', $userAndToken['user'])->first();
        $this->assertTrue(Hash::check('changePassword', $user->password));
    }

    private function userAndToken(): array
    {
        $user = $this->createUser();
        $authResponse = $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        return [
            'user' => $user->email,
            'token' => $authResponse->json('access_token')
        ];
    }

    private function createUser(): User
    {
        return User::factory()->create();
    }
}
