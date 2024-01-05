<?php

use App\Enums\CouponTypes;
use App\Enums\DiscountTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', CouponTypes::getAll());
            $table->enum('discount_type', DiscountTypes::getAll());
            $table->decimal('discount', 8, 2);
            $table->unsignedInteger('quota');
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
