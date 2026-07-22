<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengumuman;
use Faker\Factory as Faker;

class PengumumanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 0; $i < 20; $i++) {
            $is_published = $faker->boolean(80); // 80% chance to be published

            Pengumuman::create([
                'judul' => $faker->realText(50),
                'konten' => $faker->paragraphs(3, true),
                'file_lampiran' => null, // Biarkan null untuk data dummy
                'is_published' => $is_published,
                'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
