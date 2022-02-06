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
        // //Start point of our date range.
        // $start = strtotime("10 September 2000");
        
        // //End point of our date range.
        // $end = strtotime("22 July 2010");
        
        // //Custom range.
        // $timestamp = mt_rand($start, $end);
        
        // //Print it out.
        // date("Y-m-d", $timestamp)

        return [
            'nickname' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->email(),
            'sex' => $this->faker->numberBetween(1, 2),
            'birthday' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'uuid' => (string) Str::uuid(),
            'password' => Hash::make('12345678'),
            'is_active' => $this->faker->boolean(),
            'role_id' => Role::all()->random(1)->first()->id,
        ];
    }
}
