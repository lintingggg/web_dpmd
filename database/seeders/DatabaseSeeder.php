<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Superadmin (Existing)
        User::updateOrCreate(
            ['email' => 'imuh6500@gmail.com'],
            [
                'name' => 'Super Admin DPMD',
                'password' => Hash::make(env('SUPERADMIN_PASSWORD', 'admin123')),
                'role' => 'superadmin',
            ]
        );


    }
}
