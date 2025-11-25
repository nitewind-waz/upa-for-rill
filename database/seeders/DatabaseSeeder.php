<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            JurusanSeeder::class,
            ProdiSeeder::class,
            KelasSeeder::class,
            MahasiswaSeeder::class,
            EptResultMahasiswaSeeder::class,
            PublikSeeder::class,
            EptResultPublikSeeder::class,
            GedungSeeder::class,
            RuangSeeder::class,
            CreateAdmin::class,
            EptScheduleSeeder::class,
        ]);
    }
}
