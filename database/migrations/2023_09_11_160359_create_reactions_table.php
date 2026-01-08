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
        Schema::create('reactions', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->string('emoji_id')->nullable()->default(null)->comment('絵文字ID');
            $table->unsignedInteger('emoji_skin')->nullable()->default(null)->comment('絵文字スキン');
            $table->unsignedInteger('post_id')->nullable()->default(null)->comment('投稿ID');
            $table->unsignedInteger('user_id')->nullable()->default(null)->comment('ユーザーID');
            $table->unsignedtinyInteger('del_flg')->default(0)->comment('削除フラグ');
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
        Schema::dropIfExists('reactions');
    }
};
