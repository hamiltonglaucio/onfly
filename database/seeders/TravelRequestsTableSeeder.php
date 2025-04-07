<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\TravelService\Infrastructure\Eloquent\TravelRequest;

class TravelRequestsTableSeeder extends Seeder
{
    public function run(): void
    {
        TravelRequest::factory()->create([
            'user_id' => 1,
            'applicant_name' => 'Usuário Teste',
            'destination' => 'Recife',
            'departure_date' => now()->addDays(10),
            'return_date' => now()->addDays(12),
            'status' => 'request',
        ]);

        TravelRequest::factory(10)->create();
    }
}

