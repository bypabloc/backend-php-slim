<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;

class PermissionFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->domainWord(),
            'alias' => $this->faker->unique()->domainWord(),
            'is_active' => $this->faker->boolean(),
            'created_by' => User::all()->random(1)->first()->id,
        ];
    }
}
