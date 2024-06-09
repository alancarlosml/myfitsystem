<?php

namespace Database\Factories;

use App\Models\Exercise;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExerciseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Exercise::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'establishment_id' => function () {
                return \App\Models\Establishment::all()->random()->id;
            },
            'category_id' => function () {
                return \App\Models\Category::all()->random()->id;
            },
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'exercise_picture' => $this->faker->imageUrl(), // Pode adicionar um link para uma imagem aleatória, se desejar
            'active' => true,
        ];
    }
}
