<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    { 
        $roles = ['1', '2', '3', '4', '5', '6'];
        shuffle($roles); // Shuffle the roles array
 
        $name     = $this->faker->name();  
        $password = rand(999, 10000);  

        return [
            'name'          => $name,
            'email'         => $this->faker->unique()->safeEmail(),
            'phone'         => $this->faker->unique()->phoneNumber,
            'username'      => generateCustomUsername($name) ,   
            'password'      => Hash::make($password), // password
            'remember_token'=> Str::random(10),
            'role'          => $roles[0],
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
