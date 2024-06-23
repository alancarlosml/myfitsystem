<?php

namespace Database\Factories;

use App\Models\Establishment;
use App\Models\Workout;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkoutFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Workout::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $establishment = Establishment::all()->random();

        // Use whereHas() para filtrar os instrutores com base na função 'instrutor'
        $instructorIds = $establishment->users()->whereHas('roles', function ($query) {
            $query->where('name', 'instrutor');
        })->pluck('users.id')->toArray();

        // Verificar se há instrutores disponíveis
        if (empty($instructorIds)) {
            throw new \Exception("Não há instrutores associados ao estabelecimento com o ID {$establishment->id}");
        }

        $studentIds = $establishment->students()->pluck('students.id')->toArray();
        $exerciseIds = $establishment->exercises()->pluck('exercises.id')->toArray();

        // Verificar se há estudantes e exercícios disponíveis
        if (empty($studentIds)) {
            throw new \Exception("Não há estudantes associados ao estabelecimento com o ID {$establishment->id}");
        }

        if (empty($exerciseIds)) {
            throw new \Exception("Não há exercícios associados ao estabelecimento com o ID {$establishment->id}");
        }

        return [
            'establishment_id' => $establishment->id,
            'user_id' => $this->faker->randomElement($instructorIds),
            'student_id' => $this->faker->randomElement($studentIds),
            'exercise_id' => $this->faker->randomElement($exerciseIds),
            'order' => $this->faker->numberBetween(1, 10),
            'sets' => $this->faker->numberBetween(1, 5),
            'repetitions' => $this->faker->numberBetween(5, 20),
            'rest_time' => $this->faker->numberBetween(30, 120), // Tempo de descanso entre 30 e 120 segundos
            'notes' => $this->faker->sentence(),
            'active' => $this->faker->boolean,
        ];
    }

}
