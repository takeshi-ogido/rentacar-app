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
        Schema::create('option_reservation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('option_id')->constrained()->onDelete('cascade');
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->comment('オプション単価');
            $table->decimal('total_price', 10, 2)->comment('オプション小計');
            $table->timestamps();

            // 同じ予約で同じオプションが重複しないように
            $table->unique(['option_id', 'reservation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('option_reservation');
    }
};
