<?php

namespace App\Actions\Category;

use App\Enums\DiscountableTypes;
use App\Http\Requests\Category\StoreSubcategoryRequest;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Discountable;
use App\Models\Item;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class StoreSubcategory
{
    public function execute(StoreSubcategoryRequest $request): Category
    {
        $category = Category::findOrFail($request->get('parent_id'));

        $this->checkChildTypesConsistency($category);

        $this->checkOrUpdateLevel($category);

        $parentCoupon = $category->coupons()->first();

        $subcategory = Category::create([
            'name' => $request->get('name'),
            'parent_id' => $category->id,
        ]);

        if ($parentCoupon) {
            $this->inheritCoupon($parentCoupon, $subcategory);
        }

        return $subcategory;
    }

    private function checkOrUpdateLevel(Category $category): void
    {
        $currentLevel = $category->level;

        if ($currentLevel >= 4) {
            throw new UnprocessableEntityHttpException('Adding a new subcategory would exceed the maximum level of four.');
        }

        $category->updateLevel();
    }

    private function checkChildTypesConsistency(Category $category): void
    {
        $hasItems = Item::where('category_id', $category->id)
            ->exists();

        if ($hasItems) {
            throw new UnprocessableEntityHttpException('Category with items cannot have subcategories.');
        }
    }

    private function inheritCoupon(Coupon $coupon, Category $category): void
    {
        Discountable::create([
            'coupon_id' => $coupon->id,
            'type' => DiscountableTypes::CATEGORY,
            'discountable_id' => $category->id,
            'discountable_type' => $category->getMorphClass(),
        ]);
    }
}
