<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('marks', function (Blueprint $table) {
            DB::statement("ALTER TABLE marks MODIFY term ENUM('First', 'Mid', 'End', 'Monthly') NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marks', function (Blueprint $table) {
            DB::statement("ALTER TABLE marks MODIFY term ENUM('First', 'Mid', 'End') NULL");
        });
    }
};
