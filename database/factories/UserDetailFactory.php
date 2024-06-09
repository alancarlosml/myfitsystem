<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserDetailFactory extends Factory
{
    protected $model = \App\Models\UserDetail::class;

    public function definition()
    {
        $degree = ['Graduado', 'Especialista', 'Mestre', 'Doutor'];

        // Certifique-se de que há usuários com a função de instrutor
        $instructorIds = User::whereHas('roles', function ($query) {
            $query->where('role', 'instrutor');
        })->pluck('id')->toArray();

        // Retorna um ID de instrutor ou cria um novo instrutor se a lista estiver vazia
        $userId = empty($instructorIds) ? User::factory()->hasAttached(
            \App\Models\Role::where('role', 'instrutor')->first(),
            ['establishment_id' => \App\Models\Establishment::inRandomOrder()->first()->id]
        )->create()->id : $this->faker->randomElement($instructorIds);

        return [
            'user_id' => $userId,
            'profile_picture' => $this->faker->imageUrl(),
            'academic_degree' => $this->faker->randomElement($degree),
            'professional_experience' => $this->faker->paragraph,
        ];
    }
}
