<?php

namespace Database\Seeders;

use App\Models\Establishment;
use Illuminate\Database\Seeder;

class EstablishmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $establishments = [
            [
                'name' => 'Academia Fitness Center',
                'type' => 'academia',
                'owner' => 'João Silva',
                'cnpj' => '12345678000190',
                'phone' => '(11) 98765-4321',
                'address' => 'Rua das Flores, 123 - Centro, São Paulo - SP',
                'social_network' => 'https://instagram.com/fitnesscenter',
                'website' => 'https://fitnesscenter.com.br',
                'active' => true,
            ],
            [
                'name' => 'Crossfit Warriors',
                'type' => 'crossfit',
                'owner' => 'Maria Santos',
                'cnpj' => '23456789000101',
                'phone' => '(11) 98765-4322',
                'address' => 'Av. Paulista, 1000 - Bela Vista, São Paulo - SP',
                'social_network' => 'https://instagram.com/crossfitwarriors',
                'website' => null,
                'active' => true,
            ],
            [
                'name' => 'Personal Train Studio',
                'type' => 'personal_trainer',
                'owner' => 'Carlos Oliveira',
                'cnpj' => '34567890000112',
                'phone' => '(11) 98765-4323',
                'address' => 'Rua Augusta, 500 - Consolação, São Paulo - SP',
                'social_network' => null,
                'website' => 'https://personaltrain.com.br',
                'active' => true,
            ],
            [
                'name' => 'Academia Power Fit',
                'type' => 'academia',
                'owner' => 'Ana Costa',
                'cnpj' => '45678901000123',
                'phone' => '(11) 98765-4324',
                'address' => 'Rua do Comércio, 789 - Vila Nova, São Paulo - SP',
                'social_network' => 'https://facebook.com/powerfit',
                'website' => null,
                'active' => true,
            ],
            [
                'name' => 'Yoga & Pilates Space',
                'type' => 'academia',
                'owner' => 'Pedro Alves',
                'cnpj' => '56789012000134',
                'phone' => '(11) 98765-4325',
                'address' => 'Av. Rebouças, 2000 - Pinheiros, São Paulo - SP',
                'social_network' => 'https://instagram.com/yogapilatesspace',
                'website' => 'https://yogapilatesspace.com.br',
                'active' => true,
            ],
        ];

        foreach ($establishments as $establishment) {
            Establishment::firstOrCreate(
                ['cnpj' => $establishment['cnpj']],
                $establishment
            );
        }

        $this->command->info('Estabelecimentos criados com sucesso!');
    }
}
