<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Establishment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'establishment_id' => Establishment::all()->random()->id,
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'active' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }
}
