<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DirekturSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'director',
            'email' => 'melani.dian@cakrawala-internasional.co.id',
            'password' => Hash::make('ParamaDoraemon100'),
        ]);
    }
}