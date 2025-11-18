<?php

namespace Database\Seeders;

use App\Models\Establishment;
use App\Models\Student;
use App\Models\StudentEstablishment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $establishments = Establishment::all();

        // Criar alguns alunos de exemplo
        $students = [
            [
                'name' => 'João da Silva',
                'email' => 'joao@example.com',
                'password' => Hash::make('password'),
                'cpf' => '11111111111',
                'phone' => '(11) 98765-1234',
                'birthdate' => '1990-05-15',
                'address' => 'Rua das Flores, 123',
                'gender' => 'masculino',
                'active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria@example.com',
                'password' => Hash::make('password'),
                'cpf' => '22222222222',
                'phone' => '(11) 98765-2345',
                'birthdate' => '1992-08-20',
                'address' => 'Av. Paulista, 500',
                'gender' => 'feminino',
                'active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Pedro Oliveira',
                'email' => 'pedro@example.com',
                'password' => Hash::make('password'),
                'cpf' => '33333333333',
                'phone' => '(11) 98765-3456',
                'birthdate' => '1988-12-10',
                'address' => 'Rua Augusta, 200',
                'gender' => 'masculino',
                'active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ana Costa',
                'email' => 'ana@example.com',
                'password' => Hash::make('password'),
                'cpf' => '44444444444',
                'phone' => '(11) 98765-4567',
                'birthdate' => '1995-03-25',
                'address' => 'Av. Rebouças, 1000',
                'gender' => 'feminino',
                'active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Carlos Mendes',
                'email' => 'carlos@example.com',
                'password' => Hash::make('password'),
                'cpf' => '55555555555',
                'phone' => '(11) 98765-5678',
                'birthdate' => '1993-07-18',
                'address' => 'Rua do Comércio, 789',
                'gender' => 'masculino',
                'active' => true,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($students as $index => $studentData) {
            $student = Student::firstOrCreate(
                ['email' => $studentData['email']],
                $studentData
            );

            // Associar aluno ao primeiro estabelecimento (ou distribuir entre eles)
            $establishment = $establishments->get($index % $establishments->count());
            if ($establishment) {
                StudentEstablishment::firstOrCreate(
                    [
                        'student_id' => $student->id,
                        'establishment_id' => $establishment->id,
                    ],
                    [
                        'student_id' => $student->id,
                        'establishment_id' => $establishment->id,
                        'active' => true,
                    ]
                );
            }
        }

        $this->command->info('Alunos criados com sucesso!');
    }
}
