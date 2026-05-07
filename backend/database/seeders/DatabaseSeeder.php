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
        User::create([
            'name' => 'Administrator',
            'email' => 'syaiful@cakrawala-internasional.co.id',
            'password' => Hash::make('Sejauhmatamemandang1945'), 
        ]);
    }
}