<?php

namespace App\Models;

use App\Enums\DiscountableTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'category_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class, 'discountables', 'discountable_id', 'coupon_id')
            ->where('discountables.type', DiscountableTypes::ITEM)
            ->orderByDesc('discount')
            ->orderBy('expired_at')
            ->withTimestamps();
    }

    public function discountables(): MorphMany
    {
        return $this->morphMany(Discountable::class, 'discountable');
    }

    public function discount()
    {
        return $this->hasOne(Discount::class);
    }
}
