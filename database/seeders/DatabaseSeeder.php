<?php

namespace Database\Seeders;

use App\AuthService\Infrastructure\Eloquent\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            TravelRequestsTableSeeder::class,
        ]);
    }
}
