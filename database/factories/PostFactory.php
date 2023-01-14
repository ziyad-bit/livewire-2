<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'   => $this->faker->word(),
            'image'   => 'download (2).jpg',
            'slug'    => $this->faker->slug(2),
            'body'    => $this->faker->paragraph(),
            'user_id' => User::inrandomOrder()->first()->id,
        ];
    }
}
