<?php

namespace App\Actions\Category;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Models\Category;

class StoreCategory
{
    public function execute(StoreCategoryRequest $request): Category
    {
        return Category::create([
            'name' => $request->get('name'),
            'level' => 0,
        ]);
    }
}
