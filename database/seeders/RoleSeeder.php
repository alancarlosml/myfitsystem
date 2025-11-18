<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'superuser',
                'guard_name' => 'user',
            ],
            [
                'name' => 'admin',
                'guard_name' => 'user',
            ],
            [
                'name' => 'instrutor',
                'guard_name' => 'user',
            ],
            [
                'name' => 'recepcionista',
                'guard_name' => 'user',
            ],
            [
                'name' => 'assistente',
                'guard_name' => 'user',
            ],
            [
                'name' => 'nutricionista',
                'guard_name' => 'user',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name'], 'guard_name' => $role['guard_name']],
                $role
            );
        }

        $this->command->info('Roles criados com sucesso!');
    }
}
