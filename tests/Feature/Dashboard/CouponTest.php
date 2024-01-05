<?php

namespace Tests\Feature\Dashboard;

use App\Enums\CouponTypes;
use App\Enums\DiscountTypes;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Discountable;
use Tests\Feature\BaseTestCase;

class CouponTest extends BaseTestCase
{
    protected string $endpoint = '/api/dashboard/coupons/';

    public function testCanAdminCreateCoupon(): void
    {
        $this->loginAsAdmin();

        $category = Category::factory()->create();

        $payload = [
            'code' => strtoupper($this->faker->bothify('?????###')),
            'discount_type' => $this->faker->randomElement(DiscountTypes::getAll()),
            'discount' => $this->faker->numberBetween(1, 100),
            'quota' => $this->faker->numberBetween(1, 200),
            'expired_at' => now()->addWeek(),
            'type' => CouponTypes::DISCOUNTABLE,
            'discountable_id' => $category->id,
            'discountable_type' => $category->getMorphClass(),
        ];

        $this->json('POST', $this->endpoint, $payload)
            ->assertStatus(201);

        $this->assertEquals(1, Coupon::count());
        $this->assertEquals(1, Discountable::count());
    }
}
