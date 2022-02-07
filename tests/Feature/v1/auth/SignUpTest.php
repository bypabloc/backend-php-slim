<?php

namespace Tests\Feature\v1\auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class SignUpTest extends TestCase
{
    /** @test */
    public function validation_fails()
    {
        $response = $this->jsonFetch(
            'POST',
            '/api/v1/auth/sign_up',
            [
                'email' => '',
                'nickname' => '',
                'sex' => '',
                'birthday' => '',
                'password' => '',
                'passwordConfirmation' => '',
            ],
        );

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                    'nickname',
                    'sex',
                    'birthday',
                    'password',
                    'passwordConfirmation',
                ]
            ]);
    }

    /** @test */
    public function validation_success()
    {
        $user = User::factory()->make();

        $response = $this->jsonFetch(
            'POST',
            '/api/v1/auth/sign_up',
            [
                'email' => $user->email,
                'nickname' => $user->nickname,
                'sex' => $user->sex,
                'birthday' => $user->birthday,
                'password' => '12345678',
                'passwordConfirmation' => '12345678',
            ],
        );

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'user' => [
                        'email',
                        'nickname',
                    ],
                ],
            ])
            ->assertJson([
                'message' => 'User created successfully.',
                'data' => [
                    'user' => [
                        'email' => $user->email,
                        'nickname' => $user->nickname,
                    ],
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
            'nickname' => $user->nickname,
            'sex' => $user->sex,
            'birthday' => $user->birthday,
        ]);
    }

}
