<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
        * Seed the application's database.
        */
    public function run(): void
    {
        // Membuat data Admin Default untuk testing
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'your name',
                'password' => Hash::make('Password'),
                'role' => 'admin'
            ]
        );

        // Membuat akun Direktur khusus untuk switch akun di halaman login
        User::updateOrCreate(
            ['email' => 'direktur@gmail.com'],
            [
                'name' => 'your name',
                'password' => Hash::make('Password'),
                'role' => 'direktur'
            ]
        );
    }
}