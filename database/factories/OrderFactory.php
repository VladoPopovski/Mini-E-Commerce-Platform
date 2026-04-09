<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id'        => User::factory(),
            'status'         => OrderStatus::PENDING,
            'total'          => fake()->randomFloat(2, 20, 800),
            'payment_method' => fake()->randomElement(PaymentMethod::cases()),
        ];
    }

    public function paid(): static    { return $this->state(['status' => OrderStatus::PAID]); }
    public function shipped(): static { return $this->state(['status' => OrderStatus::SHIPPED]); }
}
