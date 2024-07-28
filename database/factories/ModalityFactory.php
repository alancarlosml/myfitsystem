<?php

namespace Database\Factories;

use App\Models\Modality;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModalityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Modality::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'active' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }
}
