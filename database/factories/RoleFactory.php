<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;

class RoleFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->jobTitle(),
            'is_active' => $this->faker->numberBetween(0, 1),
            'created_by' => User::all()->random(1)->first()->id,
        ];
    }
}
