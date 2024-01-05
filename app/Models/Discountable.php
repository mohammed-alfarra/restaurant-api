<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Discountable extends Model
{
    use HasFactory;

    protected $fillable = ['coupon_id', 'type', 'discountable_id', 'discountable_type'];

    public function discountable(): MorphTo
    {
        return $this->morphTo();
    }
}
