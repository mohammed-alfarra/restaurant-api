<?php

namespace Database\Factories;

use App\Enums\DiscountableTypes;
use App\Models\Category;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiscountableFactory extends Factory
{
    public function definition(): array
    {
        $category = Category::factory()->create();

        return [
            'coupon_id' => randomOrCreateFactory(Coupon::class),
            'type' => DiscountableTypes::CATEGORY,
            'discountable_id' => $category->id,
            'discountable_type' => $category->getMorphClass(),
        ];
    }
}
