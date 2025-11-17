<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        DB::table('roles')->insert([
            [
                'id_role' => 1,
                'nama_role' => 'Admin',
            ],
            [
                'id_role' => 2,
                'nama_role' => 'User',
            ]
        ]);

        DB::table('users')->insert([
            [
                'name' => 'Kingg',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password123'),
                'no_hp' => '1',
                'id_role' => 1
            ]
        ]);
    }
}
