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
            $table->json('teacherGrades')->nullable();
            $table->json('teacherClass')->nullable();
            $table->json('subjects')->nullable();
            $table->string('staffNo')->nullable();
            $table->json('medium')->nullable();
            $table->string('userId')->nullable();
            $table->string('userType')->nullable();
            $table->enum('userRole', ['admin', 'user'])->default('user')->nullable();
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
