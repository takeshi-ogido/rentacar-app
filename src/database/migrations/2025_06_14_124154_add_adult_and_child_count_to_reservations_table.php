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
            $table->unsignedTinyInteger('number_of_adults')->default(1)->after('car_id');
            $table->unsignedTinyInteger('number_of_children')->default(0)->after('number_of_adults');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['number_of_adults', 'number_of_children']);
        });
    }
};
