<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
// database/migrations/xxxx_xx_xx_create_cars_table.php

    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('color')->nullable();
            $table->integer('capacity');
            $table->integer('price');
            $table->enum('transmission', ['AT', 'MT']);
            $table->enum('smoking_preference', ['smoking', 'non-smoking']);
            $table->boolean('has_bluetooth')->default(false);
            $table->boolean('has_back_monitor')->default(false);
            $table->boolean('has_navigation')->default(false);
            $table->boolean('has_etc')->default(false);
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->string('license_plate')->nullable()->unique();
            $table->string('vin_number')->nullable()->unique();
            $table->foreignId('car_model_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('cars');
    }
};
