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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->string('user_id', 10)->unique()->nullable()->default(null)->comment('ユーザーID');
            $table->string('email', 255)->unique()->nullable(false)->comment('メールアドレス');
            $table->string('name', 50)->nullable(false)->comment('名前');
            $table->text('detail')->nullable()->default(null)->comment('自己紹介');
            $table->string('department', 50)->nullable()->default(null)->comment('部署');
            $table->date('birthday')->nullable()->default(null)->comment('誕生日');
            $table->date('hire_date')->nullable()->default(null)->comment('入社日');
            $table->rememberToken()->comment('トークン');
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
        Schema::dropIfExists('users');
    }
};
