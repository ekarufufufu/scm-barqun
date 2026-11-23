<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'New User',
            'email' => 'new.user@example.com',
            'password' => 'password123',
        ]);

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email'],
                'access_token',
                'token_type',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'new.user@example.com',
        ]);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $this->postJson('/api/login', [
            'email' => 'missing@example.com',
            'password' => 'wrong-password',
        ])->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_profile()
    {
        $register = $this->postJson('/api/register', [
            'name' => 'Profile User',
            'email' => 'profile.user@example.com',
            'password' => 'password123',
        ]);

        $token = $register->json('access_token');

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/profile')
            ->assertOk()
            ->assertJsonPath('email', 'profile.user@example.com');
    }

    public function test_guest_cannot_access_protected_routes()
    {
        $this->getJson('/api/product')->assertUnauthorized();
    }
}
