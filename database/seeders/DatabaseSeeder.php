<?php

namespace Database\Seeders;

use App\Models\VlEducation;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RoleAndPermissionSeeder::class,
            VlSkmIndicatorSeeder::class,
            VlEducationSeeder::class,
            VlOccupationSeeder::class,
        ]);
    }
}
