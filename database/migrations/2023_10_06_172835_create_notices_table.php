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
        Schema::create('notices', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->unsignedInteger('to_id')->nullable(false)->comment('通知先ID');
            $table->unsignedInteger('from_id')->nullable(false)->comment('通知元ID');
            $table->unsignedInteger('to_user_id')->nullable(false)->comment('通知先ユーザーID');
            $table->unsignedInteger('from_user_id')->nullable(false)->comment('通知元ユーザーID');
            $table->unsignedtinyInteger('type')->nullable(false)->default(0)->comment('タイプ');
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
        Schema::dropIfExists('notices');
    }
};
