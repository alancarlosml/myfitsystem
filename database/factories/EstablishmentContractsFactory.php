<?php

namespace Database\Factories;

use App\Models\Establishment;
use App\Models\EstablishmentContracts;
use Illuminate\Database\Eloquent\Factories\Factory;

class EstablishmentContractsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EstablishmentContracts::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
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

        return [
            'establishment_id' => Establishment::all()->random()->id,
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
