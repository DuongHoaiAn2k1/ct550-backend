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
        Schema::create('affiliate_wallets', function (Blueprint $table) {
            $table->id('wallet_id');
            $table->unsignedBigInteger('affiliate_user_id');
            $table->integer('balance')->default(0);
            $table->timestamps();
            $table->foreign('affiliate_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_wallets');
    }
};
