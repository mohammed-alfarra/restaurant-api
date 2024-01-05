<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Actions\Discount\ApplyCouponToCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Discount\ApplyDiscountRequest;
use App\Models\Category;

class DiscountController extends Controller
{
    public function __construct(
        private ApplyCouponToCategory $applyCouponToCategory,
    ) {
        $this->middleware(['auth:admins'])->except('download');
    }

    public function applyCouponToCategory(ApplyDiscountRequest $request, Category $category)
    {
        $this->applyCouponToCategory->execute($request, $category);

        return $this->responseSuccess();
    }
}
