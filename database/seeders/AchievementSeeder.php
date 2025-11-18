<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            // Achievements de aulas
            [
                'name' => 'Primeiro Passo',
                'description' => 'Complete sua primeira aula',
                'icon' => '🎯',
                'badge_color' => 'green',
                'type' => 'classes',
                'required_value' => 1,
                'active' => true,
            ],
            [
                'name' => 'Iniciante',
                'description' => 'Complete 10 aulas',
                'icon' => '⭐',
                'badge_color' => 'blue',
                'type' => 'classes',
                'required_value' => 10,
                'active' => true,
            ],
            [
                'name' => 'Dedicado',
                'description' => 'Complete 25 aulas',
                'icon' => '🌟',
                'badge_color' => 'purple',
                'type' => 'classes',
                'required_value' => 25,
                'active' => true,
            ],
            [
                'name' => 'Comprometido',
                'description' => 'Complete 50 aulas',
                'icon' => '💪',
                'badge_color' => 'orange',
                'type' => 'classes',
                'required_value' => 50,
                'active' => true,
            ],
            [
                'name' => 'Mestre',
                'description' => 'Complete 100 aulas',
                'icon' => '👑',
                'badge_color' => 'gold',
                'type' => 'classes',
                'required_value' => 100,
                'active' => true,
            ],
            [
                'name' => 'Lendário',
                'description' => 'Complete 100 aulas neste ano',
                'icon' => '🏆',
                'badge_color' => 'red',
                'type' => 'classes',
                'required_value' => 100,
                'active' => true,
            ],
            [
                'name' => 'Campeão Anual',
                'description' => 'Complete 200 aulas neste ano',
                'icon' => '🥇',
                'badge_color' => 'gold',
                'type' => 'classes',
                'required_value' => 200,
                'active' => true,
            ],

            // Achievements de streak
            [
                'name' => 'Começando',
                'description' => 'Mantenha um streak de 2 semanas',
                'icon' => '🔥',
                'badge_color' => 'orange',
                'type' => 'streak',
                'required_value' => 2,
                'active' => true,
            ],
            [
                'name' => 'Consistente',
                'description' => 'Mantenha um streak de 4 semanas',
                'icon' => '🔥🔥',
                'badge_color' => 'red',
                'type' => 'streak',
                'required_value' => 4,
                'active' => true,
            ],
            [
                'name' => 'Determinado',
                'description' => 'Mantenha um streak de 8 semanas',
                'icon' => '🔥🔥🔥',
                'badge_color' => 'purple',
                'type' => 'streak',
                'required_value' => 8,
                'active' => true,
            ],
            [
                'name' => 'Veterano',
                'description' => 'Mantenha um streak de 12 semanas',
                'icon' => '🔥🔥🔥🔥',
                'badge_color' => 'gold',
                'type' => 'streak',
                'required_value' => 12,
                'active' => true,
            ],

            // Achievements de treinos
            [
                'name' => 'Treinador',
                'description' => 'Complete 50 treinos',
                'icon' => '💪',
                'badge_color' => 'blue',
                'type' => 'workouts',
                'required_value' => 50,
                'active' => true,
            ],
            [
                'name' => 'Atleta',
                'description' => 'Complete 100 treinos',
                'icon' => '🏋️',
                'badge_color' => 'gold',
                'type' => 'workouts',
                'required_value' => 100,
                'active' => true,
            ],

            // Achievements de avaliações
            [
                'name' => 'Monitorando',
                'description' => 'Complete 6 avaliações físicas',
                'icon' => '📊',
                'badge_color' => 'green',
                'type' => 'assessments',
                'required_value' => 6,
                'active' => true,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::updateOrCreate(
                [
                    'name' => $achievement['name'],
                    'type' => $achievement['type'],
                ],
                $achievement
            );
        }
    }
}
