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
        Schema::create('gradebooks', function (Blueprint $table) {
            $table->id();
            // Course
    $table->foreignId('course_id')
        ->constrained('courses')
        ->cascadeOnDelete();


    // Student
    $table->foreignId('student_id')
        ->constrained('students')
        ->cascadeOnDelete();


    // Final Score
    $table->decimal(
        'final_score',
        5,
        2
    )
    ->default(0);


    // Letter Grade
    // A, B+, B...
    $table->string(
        'letter_grade'
    )
    ->nullable();


    // GPA
    $table->decimal(
        'gpa',
        3,
        2
    )
    ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gradebooks');
    }
};
