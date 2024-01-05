<?php

namespace App\Http\Resources\Discount;

use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'discount' => $this->discount,
            'total_after_discount' => $this->total_after_discount,
        ];
    }
}
