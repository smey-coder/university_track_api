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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('assignment_code')->unique();

            $table->foreignId('course_id')->constrained('courses','id')->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('teachers','id')->nullOnDelete();

            $table->string('title');
            $table->longText('description');

            $table->dateTime('due_date');
            $table->decimal('total_score', 5, 2)->default(100);

            $table->enum('status', ['Open','Closed'])->default('Open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
