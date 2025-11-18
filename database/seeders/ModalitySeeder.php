<?php

namespace Database\Seeders;

use App\Models\Modality;
use Illuminate\Database\Seeder;

class ModalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modalities = [
            [
                'name' => 'Musculação',
                'description' => 'Treinamento com pesos e equipamentos para desenvolvimento muscular',
                'active' => true,
            ],
            [
                'name' => 'Yoga',
                'description' => 'Prática física e mental focada em flexibilidade e bem-estar',
                'active' => true,
            ],
            [
                'name' => 'Crossfit',
                'description' => 'Treino funcional de alta intensidade',
                'active' => true,
            ],
            [
                'name' => 'Pilates',
                'description' => 'Método de exercício físico que trabalha o corpo de forma integrada',
                'active' => true,
            ],
            [
                'name' => 'Artes Marciais',
                'description' => 'Aulas de diversas artes marciais como Jiu-Jitsu, Muay Thai, etc.',
                'active' => true,
            ],
            [
                'name' => 'Dança',
                'description' => 'Aulas de dança em diversos estilos',
                'active' => true,
            ],
            [
                'name' => 'Spinning',
                'description' => 'Aulas de ciclismo indoor',
                'active' => true,
            ],
            [
                'name' => 'Funcional',
                'description' => 'Treinamento funcional com movimentos naturais do corpo',
                'active' => true,
            ],
        ];

        foreach ($modalities as $modality) {
            Modality::firstOrCreate(
                ['name' => $modality['name']],
                $modality
            );
        }

        $this->command->info('Modalidades criadas com sucesso!');
    }
}
