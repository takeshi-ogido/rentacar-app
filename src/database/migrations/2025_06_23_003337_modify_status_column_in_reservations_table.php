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
            // 既存のstatusカラムを削除して、enumで再作成
            $table->dropColumn('status');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending')->after('user_id');
            $table->text('notes')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['status', 'notes']);
            $table->string('status')->default('confirmed');
        });
    }
};
