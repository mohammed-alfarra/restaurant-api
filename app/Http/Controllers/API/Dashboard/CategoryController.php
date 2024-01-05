<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Actions\Category\StoreCategory;
use App\Actions\Category\StoreSubcategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\StoreSubcategoryRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function __construct(
        private StoreCategory $storeCategory,
        private StoreSubcategory $storeSubcategory
    ) {
        $this->middleware(['auth:admins']);
    }

    public function index(): AnonymousResourceCollection
    {
        $categories = Category::with(['parent', 'items.discount'])->get();

        return CategoryResource::collection($categories);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->storeCategory->execute($request);

        return $this->responseCreated(null, new CategoryResource($category));
    }

    public function storeSubcategory(StoreSubcategoryRequest $request): JsonResponse
    {
        $subcategory = $this->storeSubcategory->execute($request);

        return $this->responseCreated(null, new CategoryResource($subcategory));
    }
}
