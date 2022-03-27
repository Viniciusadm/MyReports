<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'report' => $this->faker->paragraph,
            'humor' => $this->faker->randomElement(['anxious', 'afraid', 'excited', 'happy', 'scared', 'nervous', 'neutral', 'thoughtful', 'angry', 'surprised', 'sad']),
            'type' => $this->faker->randomElement(['daily']),
        ];
    }
}
