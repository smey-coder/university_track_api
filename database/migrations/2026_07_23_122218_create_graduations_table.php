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
        Schema::create('graduations', function (Blueprint $table) {
            $table->id();

    // Student
    $table->foreignId('student_id')
        ->constrained('students')
        ->cascadeOnDelete();


    // Graduation Date
    $table->date('graduation_date')
        ->nullable();


    // Total Credits Completed
    $table->integer('completed_credits')
        ->default(0);


    // Final GPA
    $table->decimal(
        'final_gpa',
        3,
        2
    )
    ->default(0);


    // Status

    $table->enum(
        'status',
        [
            'Pending',
            'Approved',
            'Rejected'
        ]
    )
    ->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graduations');
    }
};
