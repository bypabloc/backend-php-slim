<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Models\Role;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'nickname' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->email(),
            'sex' => $this->faker->numberBetween(1, 2),
            'birthday' => $this->faker->dateTimeBetween($startDate = '-50 years', $endDate = 'now', $timezone = null),
            'uuid' => (string) Str::uuid(),
            'password' => Hash::make('12345678'),
            'is_active' => $this->faker->boolean(),
            'role_id' => Role::all()->random(1)->first()->id,
        ];
    }
}
