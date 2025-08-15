<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Color>
 */
class ColorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['red', 'blue', 'green', 'yellow', 'black', 'white', 'purple', 'orange', 'pink', 'brown']),
            'color_code' => fake()->hexColor(),
        ];
    }

    /**
     * Indicate that the color is red.
     */
    public function red(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'red',
            'color_code' => '#ff0000',
        ]);
    }

    /**
     * Indicate that the color is blue.
     */
    public function blue(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'blue',
            'color_code' => '#0000ff',
        ]);
    }

    /**
     * Indicate that the color is green.
     */
    public function green(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'green',
            'color_code' => '#00ff00',
        ]);
    }
}
