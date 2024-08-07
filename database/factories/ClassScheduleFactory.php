<?php

namespace Database\Factories;

use App\Models\ClassSchedule;
use App\Models\Establishment;
use App\Models\Modality;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClassSchedule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $startTime = $this->faker->dateTimeThisMonth();
    
        $startHour = Carbon::instance($startTime)->format('H:i');
        
        $endTime = Carbon::instance($startTime)->addHours($this->faker->numberBetween(1, 4));
        
        $endHour = $endTime->format('H:i');
        
        return [
            'modality_id' => Modality::all()->random()->id,
            'establishment_id' => Establishment::all()->random()->id,
            'user_id' => User::join('role_user', 'users.id', '=', 'role_user.user_id')->join('roles', 'role_user.role_id', '=', 'roles.id')->where('roles.name', 'instrutor')->inRandomOrder()->first()->id,
            'description' => $this->faker->sentence(),
            'class_date' => $this->faker->dateTimeThisMonth(),
            'start_time' => $startHour,
            'end_time' => $endHour,
        ];
    }
}
