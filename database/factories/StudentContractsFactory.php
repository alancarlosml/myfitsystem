<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Establishment;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentContractsFactory extends Factory
{
    protected $model = \App\Models\StudentContracts::class;

    public function definition()
    {
        // Obter o ID de um estabelecimento aleatório
        $establishmentId = Establishment::all()->random()->id;

        // Verificar se há alunos associados ao estabelecimento
        $studentIds = Student::whereHas('student_establishments', function ($query) use ($establishmentId) {
            $query->where('establishment_id', $establishmentId);
        })->pluck('id');

        // Verificar se há alunos associados ao estabelecimento
        if ($studentIds->isEmpty()) {
            throw new \Exception("Não há alunos associados ao estabelecimento com o ID {$establishmentId}");
        }

        // Obter o ID de um aluno aleatório
        $studentId = $studentIds->random();

        // Definir outros atributos da fábrica
        $serviceNames = ['semanal', 'mensal', 'trimestral', 'semestral', 'anual'];
        $paymentTypes = ['credito', 'debito', 'pix', 'boleto', 'dinheiro'];

        $serviceName = $this->faker->randomElement($serviceNames);
        $startDate = $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d');
        $paymentDate = $this->faker->dateTimeBetween('-1 year', $startDate)->format('Y-m-d');

        switch ($serviceName) {
            case 'semanal':
                $endDate = $this->faker->dateTimeBetween($startDate, '+7 days')->format('Y-m-d');
                break;
            case 'mensal':
                $endDate = $this->faker->dateTimeBetween($startDate, '+1 month')->format('Y-m-d');
                break;
            case 'trimestral':
                $endDate = $this->faker->dateTimeBetween($startDate, '+3 months')->format('Y-m-d');
                break;
            case 'semestral':
                $endDate = $this->faker->dateTimeBetween($startDate, '+6 months')->format('Y-m-d');
                break;
            case 'anual':
                $endDate = $this->faker->dateTimeBetween($startDate, '+1 year')->format('Y-m-d');
                break;
            default:
                // Se o tipo de serviço for desconhecido, defina end_date como null
                $endDate = null;
                break;
        }

        // Retornar os atributos da fábrica
        return [
            'establishment_id' => $establishmentId,
            'student_id' => $studentId,
            'service_name' => $serviceName,
            'amount' => $this->faker->randomFloat(2, 100, 1000),
            'payment_date' => $paymentDate,
            'payment_type' => $this->faker->randomElement($paymentTypes),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'active' => $this->faker->boolean(90),
            'created_at' => $paymentDate,
        ];
    }
}
