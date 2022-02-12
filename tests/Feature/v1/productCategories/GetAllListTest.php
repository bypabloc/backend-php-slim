<?php

namespace Tests\Feature\v1\productCategories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetAllListTest extends TestCase
{
    /** @test */
    public function get_all_list()
    {
        $response = $this->jsonFetch(
            'GET',
            '/api/v1/products_categories'
        );

        $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'products_categories' => [
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'slug',
                            'is_active',
                            'parent_id',
                            'user_id',
                            'created_by',
                        ]
                    ],
                ],
            ],
        ]);

    }
}
