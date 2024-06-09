<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $genders = ['masculino', 'feminino', 'outro'];

        return [
            'name' => $this->faker->name,
            'cpf' => $this->faker->unique()->numerify('###########'), // Assuming 11 digits CPF
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Change 'password' to your desired default password
            'birthdate' => $this->faker->date(),
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'profile_picture' => $this->faker->imageUrl(), // Assuming you want a random image URL
            'gender' => $this->faker->randomElement($genders),
            'active' => $this->faker->boolean(90),
            'remember_token' => Str::random(10),
        ];
    }
}
