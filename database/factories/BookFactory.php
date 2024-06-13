<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title" => fake()->sentence(2),
            "author" => fake()->name(),
            "genre" => "Fantasy",
            "description" => fake()->sentence(6),
            "isbn" => "123-4-56-789012-3",
            "publisher" => "Tor Books",
            "published" => fake()->date(),
            "image" => "img.png",
            "num_available" => 10
        ];
    }
}
