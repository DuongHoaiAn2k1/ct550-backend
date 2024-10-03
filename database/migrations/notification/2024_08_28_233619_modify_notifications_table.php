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
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('is_read');
            $table->boolean('is_admin_read')->default(false);
            $table->boolean('is_user_read')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->boolean('is_read')->default(false);
            $table->dropColumn('is_admin_read');
            $table->dropColumn('is_user_read');
        });
    }
};