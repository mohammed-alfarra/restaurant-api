<?php

namespace App\Http\Requests\Category;

use App\Rules\MaxSubcategoryLevel;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubcategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'parent_id' => [
                'required',
                'exists:categories,id',
                new MaxSubcategoryLevel($this->input('parent_id')),
            ],
        ];
    }
}
