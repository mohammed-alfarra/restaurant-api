<?php

namespace App\Repositories;

use App\Models\Coupon;
use Illuminate\Database\Query\JoinClause;

class CouponRepository
{
    public function getCouponByCode(string $code, string $discountableType, string $couponType): Coupon
    {
        return Coupon::select('coupons.*')
            ->join('discountables', function (JoinClause $join) use ($discountableType) {
                $join->on('coupons.id', '=', 'discountables.coupon_id')
                    ->where('discountables.type', '=', $discountableType);
            })
            ->where('coupons.code', '=', $code)
            ->where('coupons.type', '=', $couponType)
            ->firstOrFail();
    }
}
