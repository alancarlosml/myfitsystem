<?php

namespace Database\Factories;

use App\Models\Instructor;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    protected static $index = 0;

    public function definition()
    {
        $roles = ['superuser', 'admin', 'instrutor', 'recepcionista', 'assistente', 'nutricionista'];
        
        // Use o contador estático para obter um item da lista de roles
        $role = $roles[self::$index];

        // Incrementa o contador
        self::$index++;

        return [
            'role' => $role,
            'description' => $this->faker->sentence,
            'active' => $this->faker->boolean
        ];
    }
}