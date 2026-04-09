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

    public function unverified(): static
    {
        return $this->state(['email_verified_at' => null]);
    }

    public function withTwoFactor(): static
    {
        return $this->state([
            'two_factor_secret'         => encrypt('fake-secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
            'two_factor_confirmed_at'   => now(),
        ]);
    }
}
