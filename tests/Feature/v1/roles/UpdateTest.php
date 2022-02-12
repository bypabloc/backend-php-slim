<?php

namespace Tests\Feature\v1\roles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Role;

class UpdateTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->authorize();
    }

    /** @test */
    public function validation_fails()
    {
        $roleOld = Role::all()->random();

        $roleNew = Role::factory()->make();

        $response = $this->fetch(
            method: 'POST',
            uri: '/api/v1/roles/update',
            data: [
                'name' => $roleNew->name,
            ],
            auth: true,
        );

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'id',
                ],
            ]);
    }

    /** @test */
    public function validation_success()
    {
        $roleOld = Role::all()->random();

        $roleNew = Role::factory()->make();

        $response = $this->fetch(
            method: 'POST',
            uri: '/api/v1/roles/update',
            data: [
                'id' => $roleOld->id,
                'name' => $roleNew->name,
            ],
            auth: true,
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'role' => [
                        'name',
                    ],
                ],
            ])
            ->assertJson([
                'message' => 'Role updated successfully.',
                'data' => [
                    'role' => [
                        'id' => $roleOld->id,
                        'name' => $roleNew->name,
                    ],
                ],
            ]);

        $this->assertDatabaseHas('roles', [
            'id' => $roleOld->id,
            'name' => $roleNew->name,
        ]);
    }

    /** @test */
    public function validation_is_active_fails()
    {
        $roleOld = Role::all()->random();

        $roleNew = Role::factory()->make();

        $response = $this->fetch(
            method: 'POST',
            uri: '/api/v1/roles/update',
            data: [
                'id' => $roleOld->id,
                'is_active' => 3,
            ],
            auth: true,
        );

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'is_active',
                ],
            ]);
    }

    /** @test */
    public function validation_is_active_success()
    {
        $roleOld = Role::all()->random();

        $roleNew = Role::factory()->make();

        $response = $this->fetch(
            method: 'POST',
            uri: '/api/v1/roles/update',
            data: [
                'id' => $roleOld->id,
                'is_active' => $roleNew->is_active,
            ],
            auth: true,
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'role' => [
                        'name',
                        'is_active',
                    ],
                ],
            ])
            ->assertJson([
                'message' => 'Role updated successfully.',
                'data' => [
                    'role' => [
                        'id' => $roleOld->id,
                        'is_active' => $roleNew->is_active,
                    ],
                ],
            ]);
    }
}
