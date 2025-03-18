<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'username' => 'lara',
            'name' => 'Lara Admin',
            'email' => 'lara@example.com',
            'password' => Hash::make('adminadmin'),
        ]);
    }
}
