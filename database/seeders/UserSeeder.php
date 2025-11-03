<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'David',
            'email' => 'david@example.com',
        ]);

        User::factory()->create([
            'name' => 'Henry',
            'email' => 'henry@example.com',
        ]);

        User::factory()->create([
            'name' => 'Armando',
            'email' => 'armando@example.com',
            'role' => 'instructor',
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        User::factory()->count(10)->create();

        User::factory()->count(10)->create([
            'role' => 'instructor',
        ]);
    }
}
