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
        Establishment::factory(5)->create();
        EstablishmentContracts::factory(10)->create();
        
        Role::factory(5)->create();
        User::factory(15)->create();
        UserDetail::factory(15)->create();

        Student::factory(50)->create();
        StudentEstablishment::factory(100)->create();
        StudentContracts::factory(150)->create();

        Modality::factory(5)->create();
        Category::factory(5)->create();
        ClassSchedule::factory(50)->create();
        ClassBooking::factory(50)->create();
        Exercise::factory(50)->create();
        Workout::factory(50)->create();

    }
}
