<?php

namespace Database\Seeders;

use App\AuthService\Infrastructure\Eloquent\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Usuário Teste',
            'email' => 'teste@onfly.com.br',
            'password' => bcrypt('123456'),
        ]);

        User::factory(3)->create();
    }
}
