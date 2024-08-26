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
        Schema::create('order_detail_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_detail_id');
            $table->unsignedBigInteger('batch_id');
            $table->integer('quantity');
            $table->foreign('order_detail_id')->references('order_detail_id')->on('order_detail')->onDelete('cascade');
            $table->foreign('batch_id')->references('batch_id')->on('batches')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_detail_batches');
    }
};
