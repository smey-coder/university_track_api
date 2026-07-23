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
        Schema::create('grade_categories', function (Blueprint $table) {
            $table->id();
            // Course
            $table->foreignId('course_id')
                ->constrained('courses')
                ->cascadeOnDelete();


            // Example:
            // Homework
            // Project
            // Final Exam
            $table->string('name');


            // Percentage
            // Example:
            // 20.00
            $table->decimal(
                'weight',
                5,
                2
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_categories');
    }
};
