<?php

namespace Database\Seeders;

use App\Models\Establishment;
use App\Models\EstablishmentContracts;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixAdminAccessSeeder extends Seeder
{
    /**
     * Run the database seeder to fix admin access issues.
     * This fixes existing admin users and their establishment relationships.
     */
    public function run(): void
    {
        $this->command->info('🔧 Corrigindo acesso de admins...');

        // Buscar todos os usuários admin
        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        
        if (!$adminRole) {
            $this->command->error('Role "admin" não encontrado!');
            return;
        }

        // Buscar todos os admins
        $admins = DB::table('role_user')
            ->where('role_id', $adminRole->id)
            ->join('users', 'role_user.user_id', '=', 'users.id')
            ->select('users.id as user_id', 'role_user.establishment_id')
            ->get();

        $this->command->info("Encontrados {$admins->count()} vínculos de admin.");

        // Garantir que o pivot active está como true
        foreach ($admins as $adminLink) {
            DB::table('role_user')
                ->where('user_id', $adminLink->user_id)
                ->where('role_id', $adminRole->id)
                ->where('establishment_id', $adminLink->establishment_id)
                ->update(['active' => true]);
        }

        $this->command->info('✅ Pivots de admin corrigidos.');

        // Garantir que cada estabelecimento tem um contrato ativo e pago
        $establishments = Establishment::all();
        $fixedCount = 0;

        foreach ($establishments as $establishment) {
            // Verificar se já tem um contrato ativo e pago válido
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

                $fixedCount++;
            }
        }

        $this->command->info("✅ {$fixedCount} contratos criados para estabelecimentos.");
        $this->command->info('🎉 Correção concluída!');
    }
}

