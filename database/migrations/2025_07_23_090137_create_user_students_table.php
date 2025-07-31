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
        Schema::create('user_students', function (Blueprint $table) {
            $table->id();
            $table->string('studentGrade')->nullable();
            $table->enum('medium', ['english', 'sinhala', 'tamil'])->nullable();
            $table->string('studentClass')->nullable();
            $table->string('studentAdmissionNo')->nullable();
            $table->string('parentNo')->nullable();
            $table->string('parentProfession')->nullable();
            $table->string('userType')->nullable();
            $table->string('userId')->nullable();
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
        Schema::dropIfExists('user_students');
    }
};
