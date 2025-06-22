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
        Schema::table('reservations', function (Blueprint $table) {
            // user_id カラムが存在することを確認してから変更
            if (Schema::hasColumn('reservations', 'user_id')) {
                $table->foreignId('user_id')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'user_id')) {
                // 元に戻す場合は、nullable(false) にするが、
                // 既存のNULLデータがあるとエラーになる可能性があるので注意
                $table->foreignId('user_id')->nullable(false)->change();
            }
        });
    }
};
