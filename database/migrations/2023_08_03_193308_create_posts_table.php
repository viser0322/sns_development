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
        Schema::create('posts', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->text('content')->comment('投稿内容')->nullable(false);
            $table->unsignedInteger('user_id')->nullable(false)->comment('ユーザーID');
            $table->unsignedtinyInteger('type')->nullable(false)->default(0)->comment('投稿タイプ');
            $table->unsignedInteger('to_posts')->nullable()->comment('元投稿ID');
            $table->unsignedtinyInteger('del_flg')->nullable(false)->default(0)->comment('削除フラグ');
            $table->dateTime('deleted_at')->nullable()->comment('削除日');
            $table->dateTime('created_at')->nullable()->comment('作成日');
            $table->dateTime('updated_at')->nullable()->comment('更新日');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
