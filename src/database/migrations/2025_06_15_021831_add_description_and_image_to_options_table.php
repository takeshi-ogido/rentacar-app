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
            Schema::table('options', function (Blueprint $table) {
                $table->text('description')->nullable()->after('is_quantity');
                $table->string('image_path')->nullable()->after('description');
            });
        }

        public function down(): void
        {
            Schema::table('options', function (Blueprint $table) {
                $table->dropColumn('description');
                $table->dropColumn('image_path');
        });
    }
};
