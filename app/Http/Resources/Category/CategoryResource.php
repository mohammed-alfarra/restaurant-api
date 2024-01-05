<?php

namespace App\Http\Resources\Category;

use App\Http\Resources\Item\ItemResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * @param  mixed  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'parent_id' => $this->parent_id,
            'level' => $this->level,
            'parent' => new CategoryResource($this->whenLoaded('parent')),
            'items' => ItemResource::collection($this->whenLoaded('items')),
            'created_at' => dateTimeFormat($this->created_at),
            'updated_at' => dateTimeFormat($this->updated_at),
        ];
    }
}
