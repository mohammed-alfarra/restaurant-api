<?php

namespace App\Actions\Coupon;

use App\Enums\CouponTypes;
use App\Http\Requests\Coupon\CreateCouponRequest;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Discountable;
use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CreateCoupon
{
    public function execute(CreateCouponRequest $request): Coupon
    {
        if ($request->get('type') == CouponTypes::ALL) {
            return Coupon::create([
                'code' => $request->get('code'),
                'type' => $request->get('type'),
                'discount_type' => $request->get('discount_type'),
                'discount' => $request->get('discount'),
                'quota' => $request->get('quota'),
                'expired_at' => $request->get('expired_at'),
            ]);
        }

        $discountable = $this->findDiscountableModel(
            $request->get('discountable_type'),
            $request->get('discountable_id')
        );

        if (! $discountable) {
            throw new UnprocessableEntityHttpException('Discountable model is not found');
        }

        $coupon = Coupon::create([
            'code' => $request->get('code'),
            'type' => $request->get('type'),
            'discount_type' => $request->get('discount_type'),
            'discount' => $request->get('discount'),
            'quota' => $request->get('quota'),
            'expired_at' => $request->get('expired_at'),
        ]);

        Discountable::create([
            'coupon_id' => $coupon->id,
            'type' => $request->get('discountable_type'),
            'discountable_id' => $discountable->id,
            'discountable_type' => $discountable->getMorphClass(),
        ]);

        return $coupon;
    }

    private function findDiscountableModel(string $type, int $discountableId): ?Model
    {
        return match ($type) {
            'category' => Category::find($discountableId),
            'item' => Item::find($discountableId)
        };
    }
}
