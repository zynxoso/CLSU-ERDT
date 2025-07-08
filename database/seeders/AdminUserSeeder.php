<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@clsu-erdt.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true
        ]);
    }
}
