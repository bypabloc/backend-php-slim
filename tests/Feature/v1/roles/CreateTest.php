<?php

namespace Tests\Feature\v1\roles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Role;

class CreateTest extends TestCase
{
    // https://auth0.com/blog/testing-laravel-apis-with-phpunit/
    
    public function test_validation_fails()
    {
        $response = $this->postJson(
            '/api/v1/roles/create',
            [
                'name' => '',
            ],
        );

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                ]
            ]);
    }

    public function test_validation_success()
    {
        $role = Role::factory()->make();

        $response = $this->postJson(
            '/api/v1/roles/create', 
            [
                'name' => $role->name,
            ]
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
    }

}
