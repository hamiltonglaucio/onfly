<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\TravelService\Infrastructure\Eloquent\TravelRequest;
use App\AuthService\Infrastructure\Eloquent\User;

class TravelRequestFactory extends Factory
{
    protected $model = TravelRequest::class;

    public function definition(): array
    {
        $departureDate = $this->faker->dateTimeBetween('+5 days', '+15 days');
        $returnDate = (clone $departureDate)->modify('+2 days');

        return [
            'user_id' => User::factory(), // cria usuário vinculado se não passar
            'applicant_name' => $this->faker->name(),
            'destination' => $this->faker->city(),
            'departure_date' => $departureDate,
            'return_date' => $returnDate,
            'status' => $this->faker->randomElement(['request', 'approved', 'cancelled']),
        ];
    }
}

