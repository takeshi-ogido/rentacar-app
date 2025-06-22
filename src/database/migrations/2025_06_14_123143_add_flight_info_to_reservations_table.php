<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('flight_number_arrival')->nullable()->after('end_time');
            $table->string('flight_number_departure')->nullable()->after('flight_number_arrival');
            $table->text('note')->nullable()->after('flight_number_departure');
        });
    }

    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['flight_number_arrival', 'flight_number_departure', 'note']);
        });
}};
