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
            RoleSeeder::class,
            UserSeeder::class,
            // MachineMasterSeeder::class,
            // ProductMasterSeeder::class,
            // SubProductMasterSeeder::class,
            // OperationMasterSeeder::class,
            // PassSheetSeeder::class,
            // MasterWiseOperationSeeder::class,

        ]);

        \App\Models\User::factory(30)->create();
    }
}
