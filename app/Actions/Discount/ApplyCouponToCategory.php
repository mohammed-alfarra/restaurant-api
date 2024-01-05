<?php

namespace App\Actions\Discount;

use App\Enums\CouponTypes;
use App\Enums\DiscountableTypes;
use App\Http\Requests\Discount\ApplyDiscountRequest;
use App\Models\Category;
use App\Models\Discount;
use App\Repositories\CouponRepository;

class ApplyCouponToCategory
{
    public function __construct(
        private CouponRepository $couponRepository,
    ) {
    }

    public function execute(ApplyDiscountRequest $request, Category $category): void
    {
        $coupon = $this->couponRepository->getCouponByCode(
            $request->get('discount_code'),
            DiscountableTypes::CATEGORY,
            CouponTypes::DISCOUNTABLE
        );

        $category->load('items');

        $discounts = [];

        foreach ($category->items as $item) {
            $discount = $coupon->calculateDiscount($item->price);

            $discounts[] = [
                'item_id' => $item->id,
                'discount' => round($item->price - $discount, 2),
                'total_after_discount' => $discount,
            ];
        }

        Discount::insert($discounts);
    }
}
