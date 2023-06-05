<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $content = '';
        for ($i = 0; $i < rand(10, 50); $i++) {
            $content = $content.' <p>'.$this->faker->sentences(rand(3,10), true).'</p>';
        }

        return [
            'titre' => $this->faker->words(rand(5, 15), true),
            'content' => $content,
            'user_id' => rand(1, User::count()),
            'image' => 'uploads/default_picture_' . rand (1,5) . '.jpg',
            'tags' => $this->faker->words(3, true)
        ];
    }
}
