<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->foreignId('category_id')->constrained('categories');
            $table->timestamps();

            $table->index('category_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
};
