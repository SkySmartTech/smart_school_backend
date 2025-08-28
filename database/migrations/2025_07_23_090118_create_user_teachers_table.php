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
        Schema::create('user_teachers', function (Blueprint $table) {
            $table->id();
            $table->string('teacherGrade')->nullable();
            $table->string('teacherClass')->nullable();
            $table->string('subject')->nullable();
            $table->string('medium')->nullable();
            $table->string('staffNo')->nullable();
            $table->string('userId')->nullable();
            $table->string('userType')->nullable();
            $table->string('modifiedBy')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_teachers');
    }
};
