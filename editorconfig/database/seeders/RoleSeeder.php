<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'Admin'],
            ['name' => 'Operator'],
            ['name' => 'Supervisor'],
            ['name' => 'HOD'],
            ['name' => 'Unit Head'],
            ['name' => 'CEO'],
            ['name' => 'Fitter'],
            ['name' => 'Helper'],
            ['name' => 'Managing Director'],
        ]);
    }
}
