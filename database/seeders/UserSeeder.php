<?php

namespace Database\Seeders;

use App\Models\Establishment;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar superuser
        $superuserRole = Role::where('name', 'superuser')->first();
        $superuser = User::firstOrCreate(
            ['email' => 'superuser@myfitsystem.com'],
            [
                'name' => 'Super Usuário',
                'email' => 'superuser@myfitsystem.com',
                'password' => Hash::make('password'),
                'cpf' => '00000000000',
                'phone' => '(11) 99999-9999',
                'active' => true,
                'email_verified_at' => now(),
            ]
        );

        if ($superuserRole) {
            // Verificar se já não tem o role através da tabela pivot
            $hasRole = DB::table('role_user')
                ->where('user_id', $superuser->id)
                ->where('role_id', $superuserRole->id)
                ->exists();
            
            if (!$hasRole) {
                $superuser->roles()->attach($superuserRole->id, [
                    'establishment_id' => null, // Superuser não precisa de establishment
                ]);
            }
        }

        // Criar admins para cada estabelecimento
        $adminRole = Role::where('name', 'admin')->first();
        $establishments = Establishment::all();

        foreach ($establishments as $index => $establishment) {
            $admin = User::firstOrCreate(
                ['email' => "admin{$index}@academia{$index}.com"],
                [
                    'name' => "Admin {$establishment->name}",
                    'email' => "admin{$index}@academia{$index}.com",
                    'password' => Hash::make('password'),
                    'cpf' => str_pad((string)(10000000000 + $index), 11, '0', STR_PAD_LEFT),
                    'phone' => "(11) 9876" . str_pad((string)(5000 + $index), 4, '0', STR_PAD_LEFT),
                    'active' => true,
                    'email_verified_at' => now(),
                ]
            );

            if ($adminRole) {
                $hasRole = DB::table('role_user')
                    ->where('user_id', $admin->id)
                    ->where('role_id', $adminRole->id)
                    ->where('establishment_id', $establishment->id)
                    ->exists();
                
                if (!$hasRole) {
                    $admin->roles()->attach($adminRole->id, [
                        'establishment_id' => $establishment->id,
                        'active' => true,
                    ]);
                } else {
                    // Garantir que active está como true se o role já existe
                    DB::table('role_user')
                        ->where('user_id', $admin->id)
                        ->where('role_id', $adminRole->id)
                        ->where('establishment_id', $establishment->id)
                        ->update(['active' => true]);
                }
            }

            // Criar UserDetail para o admin
            UserDetail::firstOrCreate(
                ['user_id' => $admin->id],
                [
                    'user_id' => $admin->id,
                    'profile_picture' => null,
                    'academic_degree' => 'Graduado',
                    'professional_experience' => 'Experiência em gestão de academias',
                ]
            );
        }

        // Criar alguns instrutores e recepcionistas
        $instructorRole = Role::where('name', 'instrutor')->first();
        $attendantRole = Role::where('name', 'recepcionista')->first();

        $instructors = [
            ['name' => 'Roberto Trainer', 'email' => 'roberto@fitnesscenter.com'],
            ['name' => 'Juliana Fitness', 'email' => 'juliana@fitnesscenter.com'],
            ['name' => 'Marcos Crossfit', 'email' => 'marcos@crossfitwarriors.com'],
            ['name' => 'Fernanda Yoga', 'email' => 'fernanda@yogapilatesspace.com'],
        ];

        $attendants = [
            ['name' => 'Paula Recepção', 'email' => 'paula@fitnesscenter.com'],
            ['name' => 'Lucas Atendimento', 'email' => 'lucas@crossfitwarriors.com'],
        ];

        foreach ($instructors as $index => $instructorData) {
            $instructor = User::firstOrCreate(
                ['email' => $instructorData['email']],
                [
                    'name' => $instructorData['name'],
                    'email' => $instructorData['email'],
                    'password' => Hash::make('password'),
                    'cpf' => str_pad((string)(20000000000 + $index), 11, '0', STR_PAD_LEFT),
                    'phone' => "(11) 9876" . str_pad((string)(6000 + $index), 4, '0', STR_PAD_LEFT),
                    'active' => true,
                    'email_verified_at' => now(),
                ]
            );

            $targetEstablishment = $establishments->first(); // Atribuir ao primeiro estabelecimento
            if ($instructorRole && $targetEstablishment) {
                $hasRole = DB::table('role_user')
                    ->where('user_id', $instructor->id)
                    ->where('role_id', $instructorRole->id)
                    ->where('establishment_id', $targetEstablishment->id)
                    ->exists();
                
                if (!$hasRole) {
                    $instructor->roles()->attach($instructorRole->id, [
                        'establishment_id' => $targetEstablishment->id,
                    ]);
                }
            }

            UserDetail::firstOrCreate(
                ['user_id' => $instructor->id],
                [
                    'user_id' => $instructor->id,
                    'profile_picture' => null,
                    'academic_degree' => collect(['Graduado', 'Especialista', 'Mestre'])->random(),
                    'professional_experience' => 'Experiência em ' . $instructorData['name'],
                ]
            );
        }

        foreach ($attendants as $index => $attendantData) {
            $attendant = User::firstOrCreate(
                ['email' => $attendantData['email']],
                [
                    'name' => $attendantData['name'],
                    'email' => $attendantData['email'],
                    'password' => Hash::make('password'),
                    'cpf' => str_pad((string)(30000000000 + $index), 11, '0', STR_PAD_LEFT),
                    'phone' => "(11) 9876" . str_pad((string)(7000 + $index), 4, '0', STR_PAD_LEFT),
                    'active' => true,
                    'email_verified_at' => now(),
                ]
            );

            $targetEstablishment = $establishments->first();
            if ($attendantRole && $targetEstablishment) {
                $hasRole = DB::table('role_user')
                    ->where('user_id', $attendant->id)
                    ->where('role_id', $attendantRole->id)
                    ->where('establishment_id', $targetEstablishment->id)
                    ->exists();
                
                if (!$hasRole) {
                    $attendant->roles()->attach($attendantRole->id, [
                        'establishment_id' => $targetEstablishment->id,
                    ]);
                }
            }

            UserDetail::firstOrCreate(
                ['user_id' => $attendant->id],
                [
                    'user_id' => $attendant->id,
                    'profile_picture' => null,
                    'academic_degree' => 'Graduado',
                    'professional_experience' => 'Experiência em atendimento ao cliente',
                ]
            );
        }

        $this->command->info('Usuários criados com sucesso!');
    }
}
