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
        User::create([
            'name' => 'Captain',
            'team_name' => 'My Team',
            'email' => 'team@mail.com',
            'password' => bcrypt('password'),
            'role' => 'team',
        ]);

        User::create([
            'name' => 'Player',
            'email' => 'player@mail.com',
            'password' => bcrypt('password'),
            'role' => 'player',
        ]);
    }
}
