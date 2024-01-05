<?php

namespace App\Models;

use App\Enums\DiscountTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'discount_type',
        'discount',
        'quota',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function discountables(): MorphMany
    {
        return $this->morphMany(Discountable::class, 'discountable');
    }

    public function isSufficient(): bool
    {
        return 0 == $this->quota;
    }

    public function isExpired(): bool
    {
        return $this->expired_at && $this->expired_at->lt(now());
    }

    public function calculateDiscount($price): float
    {
        if (DiscountTypes::FIXED == $this->discount_type) {
            return floatval($price - max($this->discount, 0));
        }

        return round(($price / 100) * max($this->discount, 0), 2);
    }
}
