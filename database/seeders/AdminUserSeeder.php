<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@distributorforklift.co.id'],
            [
                'name'              => 'Admin',
                'password'          => Hash::make('Admin@Forklift2024!'),
                'email_verified_at' => now(),
            ]
        );
    }
}
