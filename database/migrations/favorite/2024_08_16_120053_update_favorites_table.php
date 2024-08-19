<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('favorites', function (Blueprint $table) {
            // Xóa ràng buộc khóa ngoại cũ
            $table->dropForeign(['user_id']);

            // Thêm ràng buộc khóa ngoại mới với onDelete('cascade')
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('favorites', function (Blueprint $table) {
            // Xóa ràng buộc khóa ngoại mới
            $table->dropForeign(['user_id']);

            // Thêm lại ràng buộc khóa ngoại cũ
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict'); // Hoặc 'no action'
        });
    }
}
