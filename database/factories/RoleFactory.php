<?php

namespace Database\Factories;

use App\Models\Instructor;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected static $index = 0;
    protected static $roles = ['superuser', 'admin', 'instrutor', 'recepcionista', 'assistente', 'nutricionista'];

    public function definition()
    {
        $guards = ['user'];

        // Verifique se o índice está dentro do intervalo
        if (self::$index >= count(self::$roles)) {
            throw new \Exception("Todos os roles foram atribuídos. Não é possível gerar mais roles únicos.");
        }

        // Use o contador estático para obter um item da lista de roles
        $role = self::$roles[self::$index];

        // Incrementa o contador
        self::$index++;

        return [
            'name' => $role,
            'guard_name' => $guards[array_rand($guards)],
        ];
    }
}