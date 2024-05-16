<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordRecoverTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_create_token_to_password_recover(): void
    {
        $userAndToken = $this->userAndToken();
        $response = $this->json(
            'POST',
            route('recover'),
            [],
            ['HTTP_Authorization' => 'Bearer ' . $userAndToken['token']]
        );

        $this->assertDatabaseCount('password_reset_tokens', 1);
    }

    public function test_return_key_to_reset_password(): void
    {
        $userAndToken = $this->userAndToken();
        $response = $this->json(
            'POST',
            route('recover'),
            [],
            ['HTTP_Authorization' => 'Bearer ' . $userAndToken['token']]
        );

        $content = json_decode($response->getContent(), true);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertTrue(Hash::check(Carbon::now() . $userAndToken['user'], $content['reset_token']));
    }

    public function test_send_token_and_change_password(): void
    {
        $userAndToken = $this->userAndToken();
        $response = $this->jsonRequest(
            method: 'POST',
            uri: route('recover'),
            headers: ['Authorization' => 'Bearer ' . $userAndToken['token']],
        );

        $token = json_decode($response->getContent(), true)['reset_token'];

        $changePasswordResponse = $this->jsonRequest(
            method: 'POST',
            uri: route('change-password'),
            data: ['token' => $token, 'password' => 'newPassword', 'password_confirmation' => 'newPassword'],
            headers: ['Authorization' => 'Bearer ' . $userAndToken['token']],
        );

        $changePasswordResponse->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Password changed'
            ]);

        $this->assertTrue(Hash::check('newPassword', User::find(1)->password));
    }

    public function test_return_error_when_attr_required_is_incomplete(): void
    {
        $userAndToken = $this->userAndToken();
        $response = $this->jsonRequest(
            method: 'POST',
            uri: route('recover'),
            headers: ['Authorization' => 'Bearer ' . $userAndToken['token']],
        );

        $token = json_decode($response->getContent(), true)['reset_token'];

        $changePasswordResponse = $this->jsonRequest(
            method: 'POST',
            uri: route('change-password'),
            data: ['token' => $token, 'password' => 'newPassword'],
            headers: ['Authorization' => 'Bearer ' . $userAndToken['token']],
        );

        $changePasswordResponse->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function userAndToken(): array
    {
        $user = User::factory()->create();;
        $authResponse = $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        return [
            'user' => $user->email,
            'token' => $authResponse->json('access_token')
        ];
    }
}
