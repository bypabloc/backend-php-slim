<?php

namespace Tests\Feature\v1\roles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetAllTest extends TestCase
{
    /** @test */
    public function get_all()
    {
        $response = $this->fetch(
            'GET',
            '/api/v1/roles/get_all'
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'roles' => [
                        'data' => [
                            '*' => [
                                'id',
                                'name',
                                'is_active',
                                'created_by',
                            ]
                        ],
                    ],
                ],
            ]);
    }
}
