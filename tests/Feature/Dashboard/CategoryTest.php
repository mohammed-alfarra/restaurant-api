<?php

namespace Tests\Feature\Dashboard;

use App\Enums\DiscountableTypes;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Discountable;
use App\Models\Item;
use Tests\Feature\BaseTestCase;

class CategoryTest extends BaseTestCase
{
    protected string $endpoint = '/api/dashboard/categories/';

    public function testCanAdminCreateCategory(): void
    {
        $this->loginAsAdmin();

        $payload = Category::factory()->create()->toArray();

        $this->json('POST', $this->endpoint, $payload)
            ->assertSee($payload['name'])
            ->assertStatus(201);

        $this->assertNull(Category::first()->parent_id);
        $this->assertEquals(0, Category::first()->level);
    }

    public function testCanAdminCreateSubcategory(): void
    {
        $this->loginAsAdmin();

        $category = Category::factory()->create();

        $payload = [
            'name' => $this->faker->word,
            'parent_id' => $category->id,
        ];

        $this->json('POST', "{$this->endpoint}subcategory", $payload)
            ->assertSee([
                'name' => $payload['name'],
                'parent_id' => $category->id,
            ])
            ->assertStatus(201);

        $category->refresh();

        $this->assertEquals(Category::where('parent_id', $category->id)->count(), $category->level);
    }

    public function testAdminCanNotCreateSubcategoryExceedingMaxLevel(): void
    {
        $this->loginAsAdmin();

        $category = Category::factory()->create([
            'level' => 4,
        ]);

        Category::factory(4)->withSubcategory()
            ->create([
                'parent_id' => $category->id,
            ]);

        $payload = [
            'name' => $this->faker->word,
            'parent_id' => $category->id,
        ];

        $this->json('POST', "{$this->endpoint}subcategory", $payload)
            ->assertDontSee($payload['name'])
            ->assertStatus(422);

        $this->assertEquals(4, $category->level);
    }

    public function testAdminCannotCreateItemAsChildOfSubcategory(): void
    {
        $this->loginAsAdmin();

        $category = Category::factory()->create();

        Item::factory(random_int(1, 10))->create([
            'category_id' => $category->id,
        ]);

        $payload = [
            'name' => $this->faker->word,
            'parent_id' => $category->id,
        ];

        $this->json('POST', "{$this->endpoint}subcategory", $payload)
            ->assertJson(['message' => 'Category with items cannot have subcategories.'])
            ->assertStatus(422);

        $this->assertEmpty(Category::where('parent_id', $category->id)->get());
    }

    public function testCanSubcategoryInheritDiscount(): void
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
            'parent_id' => $category->id,
        ];

        $this->json('POST', "{$this->endpoint}subcategory", $payload)
            ->assertSee([
                'name' => $payload['name'],
                'parent_id' => $category->id,
            ])
            ->assertStatus(201);

        $category->refresh();

        $subcategoryQuery = Category::where('parent_id', $category->id);

        $this->assertEquals($subcategoryQuery->count(), $category->level);
        $this->assertNotNull(Discountable::where('discountable_id', $subcategoryQuery->first()->id)->first());
    }
}
