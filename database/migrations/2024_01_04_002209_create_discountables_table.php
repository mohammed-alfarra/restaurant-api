<?php

use App\Enums\DiscountableTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('discountables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained();
            $table->enum('type', DiscountableTypes::getAll());
            $table->morphs('discountable');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('discountables');
    }
};
