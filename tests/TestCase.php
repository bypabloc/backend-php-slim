<?php

namespace Tests;

use Exception;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

use App\Models\User;
use App\Models\Session;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    private Generator $faker;
    
    public function setUp() : void {
        parent::setUp();

        $this->faker = Factory::create();

        Artisan::call('migrate:rollback');
        Artisan::call('migrate');
        // Artisan::call('db:seed');

        $this->withoutExceptionHandling();
    }
    
    public function __get($key) {
        if ($key === 'faker')
            return $this->faker;
        throw new Exception('Unknown Key Requested');
    }

    public function fetch(
        $method, 
        $uri, 
        $data = [],
        $token = null,
        $headers = [],
    ) {
        $headers = array_merge($headers, [
            'Content-Type' => 'application/json',
        ]);
        if ($token){
            $headers['Authorization'] = "Bearer {$token}";
        }
        if($method === 'GET') {
            if(count($data) > 0) {
                $uri .= '?' . http_build_query($data);
            }
            return $this->json($method, $uri);
        }
        return $this->json($method, $uri, $data, $headers);
    }

    public function sessionToken() {
        $user = User::factory()->make();
        $session = Session::factory()->create([
            'user_id' => $user->id,
        ]);
        return $session->token;
    }
}
