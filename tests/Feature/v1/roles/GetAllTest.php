<?php

namespace Tests\Feature\v1\roles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetAllTest extends TestCase
{
    public function test_get_all()
    {
        $response = $this->get('/api/v1/roles/get_all');

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
