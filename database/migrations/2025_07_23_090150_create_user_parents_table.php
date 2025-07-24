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
        Schema::create('user_parents', function (Blueprint $table) {
            $table->id();
            $table->string('userType')->nullable();
            $table->string('studentAdmissionNo')->nullable();
            $table->string('parentContact')->nullable();
            $table->string('profession')->nullable();
            $table->enum('relation', ['father', 'mother', 'guardian'])->nullable();
            $table->string('userId')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_parents');
    }
};
