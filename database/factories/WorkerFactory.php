<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Worker>
 */
class WorkerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // password
            'phone' => '9' . $this->faker->randomNumber(8, true),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'availability' => $this->faker->randomElement(['available', 'not available']),
            'address' => $this->faker->address,
            'category_id' => Category::all()->random()->id,
        ];
    }
}
