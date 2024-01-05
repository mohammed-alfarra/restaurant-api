<?php

namespace App\Http\Requests\Item;

use App\Enums\DiscountTypes;
use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'price' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail): void {
                    $this->checkPercentage($value, $fail);
                },
            ],

            'category_id' => ['required', 'exists:categories,id'],
        ];
    }

    private function checkPercentage($value, $fail): void
    {
        $type = $this->get('type');

        if (DiscountTypes::PERCENTAGE === $type && $value > 100) {
            $fail(trans('validation.percentage_max_100'));
        }
    }
}
