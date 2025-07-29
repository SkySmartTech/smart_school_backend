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
        Schema::create('grade_classes', function (Blueprint $table) {
            $table->id();
            $table->string('classId')->nullable();
            $table->string('class')->nullable();
            $table->text('description')->nullable();
            $table->string('gradeId')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_classes');
    }
};
