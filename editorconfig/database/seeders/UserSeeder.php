<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder; 
use Illuminate\Support\Facades\{Hash};

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(['email' => 'deetee_industries@gmail.com'], [
            'name'     => 'Admin',
            'phone'    => '9876543210', 
            'password' =>  Hash::make('deetee@1975'),
            'role'     => '1'
        ]);
         
    }
}
