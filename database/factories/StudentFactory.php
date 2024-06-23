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
        
        $uniqueEmail = $this->faker->unique()->safeEmail;
        $uniqueCpf = $this->faker->unique()->numerify('###########'); // CPF com 11 dígitos

        // Verificar se o email já existe no banco de dados
        while (\App\Models\Student::where('email', $uniqueEmail)->exists()) {
            $uniqueEmail = $this->faker->unique()->safeEmail;
        }

        // Verificar se o CPF já existe no banco de dados
        while (\App\Models\Student::where('cpf', $uniqueCpf)->exists()) {
            $uniqueCpf = $this->faker->unique()->numerify('###########');
        }

        return [
            'name' => $this->faker->name,
            'cpf' => $uniqueCpf,
            'email' => $uniqueEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Alterar 'password' para a senha desejada
            'birthdate' => $this->faker->date(),
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'profile_picture' => $this->faker->imageUrl(), // URL aleatória para a imagem de perfil
            'gender' => $this->faker->randomElement($genders),
            'active' => $this->faker->boolean(90),
            'remember_token' => Str::random(10),
        ];
    }
}
