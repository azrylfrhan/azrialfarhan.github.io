<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <-- JANGAN LUPA TAMBAHKAN INI

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin123@gmail.com'], // <-- Cari berdasarkan email yang unik
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'), // <-- WAJIB DI-HASH
                'status' => 'approved',
                'role_id' => 1,
            ]
        );
    }
}