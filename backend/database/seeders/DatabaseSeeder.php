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
            ['email' => 'syaiful@cakrawala-internasional.co.id'],
            [
                'name' => 'Syaiful',
                'password' => Hash::make('Skyfoxmp4'),
                'role' => 'admin'
            ]
        );

        // Membuat akun Direktur khusus untuk switch akun di halaman login
        User::updateOrCreate(
            ['email' => 'melanidian@cakrawala-internasional.co.id'],
            [
                'name' => 'Direktur',
                'password' => Hash::make('ParamaDoraemon100'),
                'role' => 'direktur'
            ]
        );
    }
}