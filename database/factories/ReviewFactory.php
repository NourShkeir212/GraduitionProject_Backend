<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::all()->random()->id,
            'worker_id' => Worker::all()->random()->id,
            //  'rate' => $this->faker->randomFloat(2, 0, 5),
            'rate' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'comment' => $this->faker->text,
            'date' => '2023-12-31 02:20:00'
        ];
    }
}
