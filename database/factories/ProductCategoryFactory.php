<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;

class ProductCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fakeString = substr($this->faker->unique()->name(),0, 20);

        return [
            'name' => $fakeString,
            'slug' => $fakeString,
            'is_active' => $this->faker->boolean(),
            'created_by' => User::all()->random(1)->first()->id,
        ];
    }
}
