<?php

namespace Tests\Feature\Dashboard;

use App\Enums\DiscountableTypes;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Discountable;
use App\Models\Item;
use Tests\Feature\BaseTestCase;

class ItemTest extends BaseTestCase
{
    protected string $endpoint = '/api/dashboard/items/';

    public function testCanAdminCreateItem(): void
    {
        $this->loginAsAdmin();

        $category = Category::factory()->create();

        $payload = [
            'name' => $this->faker->word,
            'price' => $this->faker->numberBetween(1, 100),
            'category_id' => $category->id,
        ];

        $this->json('POST', $this->endpoint, $payload)
            ->assertSee($payload['name'])
            ->assertStatus(201);

        $this->assertNull(Item::first()->parent_id);
    }

    public function testCanItemInheritDiscount(): void
    {
        $this->loginAsAdmin();

        $category = Category::factory()->create();

        $coupon = Coupon::factory()->create();

        Discountable::factory()->create([
            'coupon_id' => $coupon->id,
            'type' => DiscountableTypes::CATEGORY,
            'discountable_id' => $category->id,
            'discountable_type' => $category->getMorphClass(),
        ]);

        $payload = [
            'name' => $this->faker->word,
            'price' => $this->faker->numberBetween(1, 100),
            'category_id' => $category->id,
        ];

        $this->json('POST', $this->endpoint, $payload)
            ->assertSee($payload['name'])
            ->assertStatus(201);

        $this->assertNotNull(Discountable::where('discountable_id', Item::first()->id)
            ->where('discountable_type', DiscountableTypes::ITEM)
            ->first());
    }
}
