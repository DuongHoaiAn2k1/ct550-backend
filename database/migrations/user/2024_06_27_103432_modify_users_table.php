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
        Schema::table('users', function (Blueprint $table) {
            $table->json('address')->nullable();
            $table->json('image')->nullable();
            $table->string('roles')->default('normal_customer');
            $table->integer('points')->default(0);
            $table->integer('point_used')->default(0);
            $table->string('username')->nullable();
            $table->decimal('total_payment_amount', 10, 2)->default(0.00);
            $table->decimal('online_payment_amount', 10, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('image');
            $table->dropColumn('roles');
            $table->dropColumn('points');
            $table->dropColumn('point_used');
            $table->dropColumn('username');
            $table->dropColumn('total_payment_amount');
            $table->dropColumn('online_payment_amount');
        });
    }
};
