<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Actions\Coupon\CreateCoupon;
use App\Http\Controllers\Controller;
use App\Http\Requests\Coupon\CreateCouponRequest;
use App\Http\Resources\Coupon\CouponResource;
use Illuminate\Http\JsonResponse;

class CouponController extends Controller
{
    public function __construct(private CreateCoupon $createCoupon)
    {
        $this->middleware(['auth:admins']);
    }

    public function store(CreateCouponRequest $request): JsonResponse
    {
        $coupon = $this->createCoupon->execute($request);

        return $this->responseCreated(null, new CouponResource($coupon));
    }
}
