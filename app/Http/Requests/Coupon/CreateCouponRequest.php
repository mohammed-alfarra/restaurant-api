<?php

namespace App\Http\Requests\Coupon;

use App\Enums\CouponTypes;
use App\Enums\DiscountableTypes;
use App\Enums\DiscountTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCouponRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', 'unique:coupons'],
            'type' => ['required', Rule::in(CouponTypes::getAll())],
            'discountable_type' => ['required', Rule::in(DiscountableTypes::getAll())],
            'discount_type' => ['required', Rule::in(DiscountTypes::getAll())],
            'discount' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail): void {
                    $this->checkPercentage($value, $fail);
                },
            ],
            'quota' => ['required', 'min:0', 'integer'],
            'expired_at' => ['nullable', 'after:now'],
            'discountable_id' => ['required_if:type,category,item'],
        ];
    }

    private function checkPercentage($value, $fail): void
    {
        $discountType = $this->get('discount_type');

        if (DiscountTypes::PERCENTAGE === $discountType && $value > 100) {
            $fail('The percentage must not be greater than 100.');
        }
    }
}
