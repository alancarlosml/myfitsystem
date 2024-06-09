<?php

namespace Database\Factories;

use App\Models\Establishment;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'phone' => $this->faker->phoneNumber,
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'cpf' => $this->faker->numerify('###########'),
            'active' => $this->faker->boolean(90),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            $roles = Role::where('role', '!=', 'superuser')->get(); // Evita atribuir o papel de superusuário
            $establishments = Establishment::all();

            // Atribui um papel e estabelecimento aleatório ao usuário
            if ($roles->isNotEmpty() && $establishments->isNotEmpty()) {
                $user->roles()->attach($roles->random()->id, [
                    'establishment_id' => $establishments->random()->id,
                ]);
            }
        });
    }
}
