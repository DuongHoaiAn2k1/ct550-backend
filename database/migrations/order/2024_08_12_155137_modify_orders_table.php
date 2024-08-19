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
        Schema::table('orders', function (Blueprint $table) {
            $table->json('order_address')->change();
            $table->boolean('paid')->default(false);
            $table->integer('total_cost');
            $table->integer('shipping_fee');
            $table->integer('point_used_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_address')->change();
            $table->dropColumn('paid');
            $table->dropColumn('total_cost');
            $table->dropColumn('shipping_fee');
            $table->dropColumn('point_used_order');
        });
    }
};
