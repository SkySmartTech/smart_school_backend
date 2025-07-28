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
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->string('studentAdmissionNo')->nullable();
            $table->string('studentName')->nullable();
            $table->string('studentGrade')->nullable();
            $table->string('studentClass')->nullable();
            $table->enum('term', ['First', 'Mid', 'End'])->nullable();
            $table->string('subject')->nullable();
            $table->integer('marks')->nullable();
            $table->string('marksGrade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marks');
    }
};
