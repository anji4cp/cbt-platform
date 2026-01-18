<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // ← INI YANG TADI KURANG

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@cbt.test',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);
    }
}
