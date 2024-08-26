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
        Schema::table('order_detail', function (Blueprint $table) {
            $table->dropForeign(['batch_id']);
            $table->dropColumn('batch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_detail', function (Blueprint $table) {

            Schema::table('order_detail', function (Blueprint $table) {
                $table->unsignedBigInteger('batch_id')->nullable();
                $table->foreign('batch_id')->references('batch_id')->on('batches')->onDelete('cascade');
            });
        });
    }
};
