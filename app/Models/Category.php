<?php

namespace App\Models;

use App\Enums\DiscountableTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id', 'level'];

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class, 'discountables', 'discountable_id', 'coupon_id')
            ->where('discountables.type', DiscountableTypes::CATEGORY)
            ->orderByDesc('discount')
            ->orderBy('expired_at')
            ->withTimestamps();
    }

    public function discountables(): MorphMany
    {
        return $this->morphMany(Discountable::class, 'discountable');
    }

    public function updateLevel(): void
    {
        $this->level += 1;

        $this->save();
    }
}
