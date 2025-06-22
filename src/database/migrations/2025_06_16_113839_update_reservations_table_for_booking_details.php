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
            // Rename existing columns if they exist with old names
            if (Schema::hasColumn('reservations', 'start_time') && !Schema::hasColumn('reservations', 'start_datetime')) {
                $table->renameColumn('start_time', 'start_datetime');
            }
            if (Schema::hasColumn('reservations', 'end_time') && !Schema::hasColumn('reservations', 'end_datetime')) {
                $table->renameColumn('end_time', 'end_datetime');
            }

            // Add missing columns
            // Ensure correct placement with ->after() if specific order is important,
            // otherwise they will be added at the end.
            if (!Schema::hasColumn('reservations', 'name_kana_sei')) {
                $table->string('name_kana_sei')->after('name_kanji')->comment('カタカナ姓');
            }
            if (!Schema::hasColumn('reservations', 'name_kana_mei')) {
                $table->string('name_kana_mei')->after('name_kana_sei')->comment('カタカナ名');
            }
            if (!Schema::hasColumn('reservations', 'email')) {
                $table->string('email')->after('name_kana_mei');
            }
            if (!Schema::hasColumn('reservations', 'phone_main')) {
                $table->string('phone_main')->after('email')->comment('予約者電話番号');
            }
            if (!Schema::hasColumn('reservations', 'phone_emergency')) {
                $table->string('phone_emergency')->nullable()->after('phone_main')->comment('緊急連絡先');
            }
            // Assuming controller uses flight_departure and flight_return
            if (!Schema::hasColumn('reservations', 'flight_departure')) {
                $table->string('flight_departure')->nullable()->comment('往路フライト便名');
            }
            if (!Schema::hasColumn('reservations', 'flight_return')) {
                $table->string('flight_return')->nullable()->comment('復路フライト便名');
            }
            if (!Schema::hasColumn('reservations', 'total_price')) {
                $table->integer('total_price');
            }
            if (!Schema::hasColumn('reservations', 'options_json')) {
                $table->text('options_json')->nullable();
            }
            if (!Schema::hasColumn('reservations', 'status')) {
                $table->string('status')->default('confirmed');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Drop added columns
            $table->dropColumn([
                'name_kana_sei', 'name_kana_mei', 'email', 'phone_main', 
                'phone_emergency', 'flight_departure', 'flight_return',
                'total_price', 'options_json', 'status'
            ]);

            // Rename columns back if they were renamed
            if (Schema::hasColumn('reservations', 'start_datetime') && !Schema::hasColumn('reservations', 'start_time')) {
                $table->renameColumn('start_datetime', 'start_time');
            }
            if (Schema::hasColumn('reservations', 'end_datetime') && !Schema::hasColumn('reservations', 'end_time')) {
                $table->renameColumn('end_datetime', 'end_time');
            }
        });
    }
};