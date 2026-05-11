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
                'name' => 'Administrator',
                'password' => Hash::make('Sejauhmatamemandang1945'),
                'role' => 'admin'
            ]
        );

        // Membuat akun Direktur khusus untuk switch akun di halaman login
        User::updateOrCreate(
            ['email' => 'direktur@cakrawala-internasional.co.id'],
            [
                'name' => 'Direktur',
                'password' => Hash::make('DirekturPassword2026!'),
                'role' => 'direktur'
            ]
        );
=======
        User::create([
            'name' => 'Administrator',
            'email' => 'syaiful@cakrawala-internasional.co.id',
            'password' => Hash::make('Sejauhmatamemandang1945'), 
        ]);

        // Memanggil DirekturSeeder
        $this->call([
            DirekturSeeder::class,
        ]);

    }
}