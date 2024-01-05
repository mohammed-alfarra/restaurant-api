<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Actions\Item\StoreItem;
use App\Http\Controllers\Controller;
use App\Http\Requests\Item\StoreItemRequest;
use App\Http\Resources\Item\ItemResource;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ItemController extends Controller
{
    public function __construct(private StoreItem $storeItem)
    {
        $this->middleware(['auth:admins']);
    }

    public function index(): AnonymousResourceCollection
    {
        $items = Item::with('category.parent')->get();

        return ItemResource::collection($items);
    }

    public function store(StoreItemRequest $request): JsonResponse
    {
        $item = $this->storeItem->execute($request);

        return $this->responseCreated(null, new ItemResource($item));
    }
}
