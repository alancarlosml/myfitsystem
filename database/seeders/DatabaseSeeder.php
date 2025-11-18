<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ClassBooking;
use App\Models\ClassSchedule;
use App\Models\Establishment;
use App\Models\EstablishmentContracts;
use App\Models\Exercise;
use App\Models\Modality;
use App\Models\Role;
use App\Models\Student;
use App\Models\StudentContracts;
use App\Models\StudentEstablishment;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\Workout;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Iniciando seeders...');

        // Seeders básicos (ordem importa)
        $this->call([
            RoleSeeder::class,
            AchievementSeeder::class,
            EstablishmentSeeder::class,
            UserSeeder::class,
            ModalitySeeder::class,
            CategorySeeder::class,
            StudentSeeder::class,
        ]);

        // Garantir que cada estabelecimento tenha pelo menos um contrato ativo e pago
        $this->command->info('💳 Garantindo contratos ativos para estabelecimentos...');
        $establishments = Establishment::all();
        foreach ($establishments as $establishment) {
            // Verificar se já tem um contrato ativo e pago
            $hasActiveContract = EstablishmentContracts::where('establishment_id', $establishment->id)
                ->where('active', true)
                ->where('status', 'pago')
                ->where('end_date', '>=', now()->toDateString())
                ->exists();
            
            if (!$hasActiveContract) {
                // Criar um contrato mensal ativo e pago válido por 1 ano
                $startDate = now()->toDateString();
                $endDate = now()->addYear()->toDateString();
                
                EstablishmentContracts::create([
                    'establishment_id' => $establishment->id,
                    'service_name' => 'mensal',
                    'amount' => 500.00,
                    'payment_date' => now()->toDateString(),
                    'payment_type' => 'pix',
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'active' => true,
                    'status' => 'pago',
                    'paid_at' => now(),
                ]);
            }
        }

        // Seeders com factories (dados de teste)
        $this->command->info('📦 Criando dados de teste com factories...');
        
        // Garantir que temos estabelecimentos antes de criar contratos
        if (Establishment::count() < 5) {
            Establishment::factory(5)->create();
        }
        
        EstablishmentContracts::factory(10)->create();

        // Criar mais usuários e alunos se necessário
        if (User::count() < 20) {
            User::factory(15)->create();
            UserDetail::factory(15)->create();
        }

        if (Student::count() < 50) {
            Student::factory(50)->create();
            StudentEstablishment::factory(100)->create();
            StudentContracts::factory(150)->create();
        }

        // Criar mais dados relacionados
        if (Modality::count() < 8) {
            Modality::factory(3)->create();
        }

        if (Category::count() < 8) {
            Category::factory(3)->create();
        }

        ClassSchedule::factory(50)->create();
        ClassBooking::factory(50)->create();
        Exercise::factory(50)->create();
        Workout::factory(50)->create();

        $this->command->info('✅ Seeders concluídos com sucesso!');
        $this->command->info('📝 Credenciais de acesso:');
        $this->command->info('   Superuser: superuser@myfitsystem.com / password');
        $this->command->info('   Admin: admin0@academia0.com / password');
        $this->command->info('   Aluno: joao@example.com / password');
    }
}
