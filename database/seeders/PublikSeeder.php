<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Publik; // Assuming you have a Publik model
use Faker\Factory as Faker;

class PublikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Use Indonesian locale for faker

        // Create 10 dummy publik entries
        for ($i = 0; $i < 10; $i++) {
            Publik::create([
                'nik' => $faker->unique()->numerify('##############'), // 14 digit NIK
                'nama_lengkap' => $faker->name,
            ]);
        }
    }
}