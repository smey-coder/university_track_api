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
        Schema::create('transcripts', function (Blueprint $table) {
            $table->id();
            // Student
    $table->foreignId('student_id')
        ->constrained('students')
        ->cascadeOnDelete();


    // Semester
    $table->foreignId('semester_id')
        ->constrained('semesters')
        ->cascadeOnDelete();


    // Academic Year
    $table->foreignId('academic_year_id')
        ->constrained('academic_years')
        ->cascadeOnDelete();


    // Total Credits
    $table->integer('total_credits')
        ->default(0);


    // Semester GPA
    $table->decimal(
        'semester_gpa',
        3,
        2
    )
    ->default(0);


    // Overall GPA
    $table->decimal(
        'overall_gpa',
        3,
        2
    )
    ->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transcripts');
    }
};
