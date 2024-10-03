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
        Schema::create('affiliate_withdrawals', function (Blueprint $table) {
            $table->id('withdrawal_id');
            $table->unsignedBigInteger('affiliate_user_id');
            $table->integer('amount');
            $table->string('status', 50)->default('pending');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_holder_name');
            $table->timestamps();
            $table->foreign('affiliate_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_withdrawals');
    }
};
