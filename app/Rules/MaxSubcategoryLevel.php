<?php

namespace App\Rules;

use App\Models\Category;
use Illuminate\Contracts\Validation\Rule;

class MaxSubcategoryLevel implements Rule
{
    protected $parentId;

    public function __construct($parentId)
    {
        $this->parentId = $parentId;
    }

    public function passes($attribute, $value)
    {
        $currentLevel = Category::where('id', $this->parentId)->value('level');

        return $currentLevel < 4;
    }

    public function message()
    {
        return 'Adding a new subcategory would exceed the maximum level of four.';
    }
}
