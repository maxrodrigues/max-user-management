<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_new_user(): void
    {
        $attr = $this->attributes();

        $res = $this->postJson(route('register'), $attr);
        $res->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_return_error_when_attr_required_is_incomplete(): void
    {
        $attr = [
            'name' => 'test',
            'password' => 'test',
        ];

        $res = $this->postJson(route('register'), $attr);
        $res->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
            'email' => 'The email field is required.',
        ]);

        $this->assertDatabaseCount('users', 0);
    }

    public function test_return_error_when_email_is_already_exists(): void
    {
        $attr = $this->attributes();

        $this->postJson(route('register'), $attr);
        $res = $this->postJson(route('register'), $attr);
        $res->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson([
            'message' => 'Error when register new user'
        ]);
    }

    private function attributes(): array
    {
        return [
            'name' => 'test',
            'email' => 'test@test',
            'password' => 'test',
        ];
    }
}
