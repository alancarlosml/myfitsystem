<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Peito',
                'description' => 'Exercícios para desenvolvimento dos músculos peitorais',
                'active' => true,
            ],
            [
                'name' => 'Costas',
                'description' => 'Exercícios para desenvolvimento dos músculos das costas',
                'active' => true,
            ],
            [
                'name' => 'Pernas',
                'description' => 'Exercícios para desenvolvimento dos músculos das pernas',
                'active' => true,
            ],
            [
                'name' => 'Braços',
                'description' => 'Exercícios para desenvolvimento dos músculos dos braços',
                'active' => true,
            ],
            [
                'name' => 'Ombros',
                'description' => 'Exercícios para desenvolvimento dos músculos dos ombros',
                'active' => true,
            ],
            [
                'name' => 'Abdômen',
                'description' => 'Exercícios para desenvolvimento dos músculos abdominais',
                'active' => true,
            ],
            [
                'name' => 'Cardio',
                'description' => 'Exercícios cardiovasculares',
                'active' => true,
            ],
            [
                'name' => 'Funcional',
                'description' => 'Exercícios funcionais',
                'active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }

        $this->command->info('Categorias criadas com sucesso!');
    }
}
