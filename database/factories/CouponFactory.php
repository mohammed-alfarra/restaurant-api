<?php

namespace Database\Factories;

use App\Enums\CouponTypes;
use App\Enums\DiscountTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->bothify('?????###')),
            'type' => $this->faker->randomElement(CouponTypes::getAll()),
            'discount_type' => $this->faker->randomElement(DiscountTypes::getAll()),
            'discount' => $this->faker->numberBetween(1, 100),
            'quota' => $this->faker->numberBetween(1, 200),
            'expired_at' => $this->faker->dateTimeInInterval('+1 year', '+5 year'),
        ];
    }
}
