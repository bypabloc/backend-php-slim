<?php

namespace Tests\Feature\v1\roles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Role;

class UpdateTest extends TestCase
{
    public function test_validation_fails()
    {
        $roleOld = Role::all()->random();

        $roleNew = Role::factory()->make();

        $response = $this->postJson(
            '/api/v1/roles/update', 
            [
                'name' => $roleNew->name,
            ],
        );

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'id',
                ],
            ]);
    }

    public function test_validation_success()
    {
        $roleOld = Role::all()->random();

        $roleNew = Role::factory()->make();

        $response = $this->postJson(
            '/api/v1/roles/update', 
            [
                'id' => $roleOld->id,
                'name' => $roleNew->name,
            ],
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
}
