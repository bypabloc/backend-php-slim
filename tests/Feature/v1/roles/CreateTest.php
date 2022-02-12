<?php

namespace Tests\Feature\v1\roles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Role;

class CreateTest extends TestCase
{
    // https://auth0.com/blog/testing-laravel-apis-with-phpunit/

    private string $token;

    public function setUp(): void
    {
        parent::setUp();

        $this->token = $this->sessionToken();
    }
    
    /** @test */
    public function validation_fails()
    {
        $response = $this->fetch(
            method: 'POST',
            uri: '/api/v1/roles/create',
            data: [
                'name' => '',
            ],
            token: $this->token,
        );

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                ],
            ]);
    }

    /** @test */
    public function validation_success()
    {
        $role = Role::factory()->make();

        $response = $this->fetch(
            method: 'POST',
            uri: '/api/v1/roles/create',
            data: [
                'name' => $role->name,
            ],
            token: $this->token,
        );

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'role' => [
                        'name',
                    ],
                ],
            ])
            ->assertJson([
                'message' => 'Role created successfully.',
                'data' => [
                    'role' => [
                        'name' => $role->name,
                    ],
                ],
            ]);

        $this->assertDatabaseHas('roles', [
            'name' => $role->name,
        ]);
    }

}
