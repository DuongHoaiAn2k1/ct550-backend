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
        Schema::create('affiliate_requests', function (Blueprint $table) {
            $table->bigIncrements('affiliate_request_id');
            $table->unsignedBigInteger('user_id');
            $table->string('status')->default('pending'); // 'pending', 'approved', 'rejected'
            $table->text('reason')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_requests');
    }
};
