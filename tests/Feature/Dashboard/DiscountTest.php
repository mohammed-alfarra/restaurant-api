<?php

namespace Tests\Feature\Dashboard;

use App\Enums\CouponTypes;
use App\Enums\DiscountableTypes;
use App\Enums\DiscountTypes;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Discount;
use App\Models\Discountable;
use App\Models\Item;
use Tests\Feature\BaseTestCase;

class DiscountTest extends BaseTestCase
{
    protected string $endpoint = '/api/dashboard/discounts/';

    public function testCanAdminApplyDisountToCategory(): void
    {
        $this->loginAsAdmin();

        $category = Category::factory()->create();

        $coupon = Coupon::factory()->create([
            'type' => CouponTypes::DISCOUNTABLE,
            'discount_type' => DiscountTypes::FIXED,
            'discount' => 8,
        ]);

        Discountable::factory()->create([
            'coupon_id' => $coupon->id,
            'type' => DiscountableTypes::CATEGORY,
            'discountable_id' => $category->id,
            'discountable_type' => $category->getMorphClass(),
        ]);

        $item = Item::factory()->create([
            'category_id' => $category->id,
            'price' => 100,
        ]);

        $payload = [
            'discount_code' => $coupon->code,
        ];

        $this->json('POST', "{$this->endpoint}apply-category/{$category->id}", $payload)
            ->assertStatus(200);

        $discount = Discount::first();

        $this->assertEquals($item->id, $discount->item_id);
        $this->assertEquals($coupon->discount, $discount->discount);
        $this->assertEquals(($item->price - $coupon->discount), $discount->total_after_discount);
    }
}
