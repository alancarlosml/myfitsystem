<?php

namespace Database\Factories;

use App\Models\Establishment;
use Illuminate\Database\Eloquent\Factories\Factory;

class EstablishmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Establishment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $types = ['academia', 'crossfit', 'personal_trainer'];
        
        return [
            'name' => $this->faker->company,
            'type' => $this->faker->randomElement($types),
            'owner' => $this->faker->name,
            'cnpj' => $this->faker->unique()->numerify('##############'),
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'social_network' => $this->faker->optional()->url,
            'website' => $this->faker->optional()->url,
            'active' => $this->faker->boolean(90),
        ];
    }
}
