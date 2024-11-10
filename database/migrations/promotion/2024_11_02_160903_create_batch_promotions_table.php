<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('batch_promotions', function (Blueprint $table) {
            $table->bigIncrements('batch_promotion_id');
            $table->unsignedBigInteger('promotion_id');
            $table->unsignedBigInteger('batch_id');
            $table->unsignedInteger('discount_price');

            $table->timestamps();

            $table->foreign('promotion_id')
                ->references('promotion_id')
                ->on('promotions')
                ->onDelete('cascade');

            $table->foreign('batch_id')
                ->references('batch_id')
                ->on('batches')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_promotions');
    }
};
