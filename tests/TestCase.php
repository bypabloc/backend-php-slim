<?php

namespace Tests;

use Exception;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    private Generator $faker;
    
    public function setUp() : void {
        parent::setUp();

        $this->faker = Factory::create();

        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');

        $this->withoutExceptionHandling();
    }
    
    public function __get($key) {
        if ($key === 'faker')
            return $this->faker;
        throw new Exception('Unknown Key Requested');
    }

    public function jsonFetch($method, $uri, $data = []) {
        if($method === 'GET') {
            if(count($data) > 0) {
                $uri .= '?' . http_build_query($data);
            }
            return $this->json($method, $uri);
        }
        return $this->json($method, $uri, $data);
    }
}
