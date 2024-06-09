<?php

namespace Database\Factories;

use App\Models\ClassBooking;
use App\Models\ClassSchedule;
use App\Models\Establishment;
use App\Models\Modality;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassBookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClassBooking::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Busca um aluno aleatório para associar à reserva
        $student = Student::inRandomOrder()->first();

        // Busca uma agenda de aula aleatória para associar à reserva
        $classSchedule = ClassSchedule::inRandomOrder()->first();

        // Define a data da reserva como uma data aleatória nos próximos 30 dias
        $bookingDate = $this->faker->dateTimeBetween('+1 day', '+30 days');

        // Define se o aluno já fez check-in ou não (50% de chance)
        $checkedIn = $this->faker->boolean(50);

        return [
            'student_id' => $student->id,
            'class_schedule_id' => $classSchedule->id,
            'booking_date' => $bookingDate,
            'checked_in' => $checkedIn,
        ];
    }
}
