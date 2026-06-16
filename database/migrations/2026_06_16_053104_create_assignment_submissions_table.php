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
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('submission_code')->unique();

            $table->foreignId('assignment_id')->constrained('assignments','id')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students','id')->cascadeOnDelete();

            $table->string('file_path')->nullable();
            $table->dateTime('submitted_at')->nullable();

            $table->decimal('score', 5, 2)->nullable();
            $table->text('feedback')->nullable();

            $table->enum('status', ['Submitted','Late','Graded'])->default('Submitted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};
