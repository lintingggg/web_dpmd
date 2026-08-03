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
                'password' => Hash::make('admin123'),
                'role' => 'superadmin',
            ]
        );

        // Admin Pemdes
        User::updateOrCreate(
            ['email' => 'pemdes@dpmd.bangkalan.go.id'],
            [
                'name' => 'Admin Pemdes',
                'password' => Hash::make('pemdes123'),
                'role' => 'admin_pemdes',
            ]
        );

        // Admin Pemberdayaan
        User::updateOrCreate(
            ['email' => 'pemberdayaan@dpmd.bangkalan.go.id'],
            [
                'name' => 'Admin Pemberdayaan',
                'password' => Hash::make('pemberdayaan123'),
                'role' => 'admin_pemberdayaan',
            ]
        );

        // Admin Lembaga Kemasyarakatan
        User::updateOrCreate(
            ['email' => 'lembaga@dpmd.bangkalan.go.id'],
            [
                'name' => 'Admin Lembaga Kemasyarakatan',
                'password' => Hash::make('lembaga123'),
                'role' => 'admin_lembaga',
            ]
        );

        // Admin Sekretariat
        User::updateOrCreate(
            ['email' => 'sekretariat@dpmd.bangkalan.go.id'],
            [
                'name' => 'Admin Sekretariat',
                'password' => Hash::make('sekretariat123'),
                'role' => 'admin_sekretariat',
            ]
        );
    }
}
