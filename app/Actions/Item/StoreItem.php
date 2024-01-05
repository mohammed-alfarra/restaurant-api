<?php

namespace App\Actions\Item;

use App\Enums\DiscountableTypes;
use App\Http\Requests\Item\StoreItemRequest;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Discountable;
use App\Models\Item;

class StoreItem
{
    public function execute(StoreItemRequest $request): Item
    {
        $category = Category::findOrFail($request->get('category_id'));

        $parentCoupon = $category->coupons()->first();

        $item = Item::create([
            'name' => $request->get('name'),
            'price' => $request->get('price'),
            'category_id' => $category->id,
        ]);

        if ($parentCoupon) {
            $this->inheritCoupon($parentCoupon, $item);
        }

        return $item;
    }

    private function inheritCoupon(Coupon $coupon, Item $item): void
    {
        Discountable::create([
            'coupon_id' => $coupon->id,
            'type' => DiscountableTypes::ITEM,
            'discountable_id' => $item->id,
            'discountable_type' => $item->getMorphClass(),
        ]);
    }
}
