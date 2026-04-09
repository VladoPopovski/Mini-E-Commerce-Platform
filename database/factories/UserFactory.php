<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'role'              => UserRole::BUYER,
        ];
    }

    public function vendor(): static { return $this->state(['role' => UserRole::VENDOR]); }
    public function admin(): static  { return $this->state(['role' => UserRole::ADMIN]); }
}
