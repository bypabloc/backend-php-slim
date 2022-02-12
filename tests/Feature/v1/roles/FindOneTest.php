<?php

namespace Tests\Feature\v1\roles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Role;

class FindOneTest extends TestCase
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
            method: 'GET',
            uri: '/api/v1/roles/find_one',
            data: [
                'id' => $roleNew->id,
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
        $roleOld = Role::factory()->create();

        $response = $this->fetch(
            method: 'GET',
            uri: '/api/v1/roles/find_one',
            data: [
                'id' => $roleOld->id,
            ],
            auth: true,
        );
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'role' => [
                        'id',
                        'name',
                    ],
                ],
            ])
            ->assertJson([
                'message' => 'Role found successfully.',
                'data' => [
                    'role' => [
                        'id' => $roleOld->id,
                        'name' => $roleOld->name,
                    ],
                ],
            ]);

        $this->assertDatabaseHas('roles', [
            'id' => $roleOld->id,
            'name' => $roleOld->name,
        ]);
    }
}
