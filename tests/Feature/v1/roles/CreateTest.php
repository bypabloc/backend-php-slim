<?php

namespace Tests\Feature\v1\roles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Role;
use App\Models\Permission;

class CreateTest extends TestCase
{
    // https://auth0.com/blog/testing-laravel-apis-with-phpunit/

    public function setUp(): void
    {
        parent::setUp();
        $this->authorize();
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
            auth: true,
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
        $permissions = Permission::factory()
        ->count(5)
        ->create();

        $permissions = collect($permissions)->map(function ($permission) {
            return $permission->id;
        });

        $response = $this->fetch(
            method: 'POST',
            uri: '/api/v1/roles/create',
            data: [
                'name' => $role->name,
                'permissions' => $permissions->toArray(),
            ],
            auth: true,
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
