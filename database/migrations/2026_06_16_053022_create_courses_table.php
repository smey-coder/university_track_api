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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_code')->unique();
            $table->foreignId('department_id')->constrained('departments','id')->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('teachers','id')->nullOnDelete();

            $table->string('course_name');
            $table->integer('credits')->default(3);
            $table->text('description')->nullable();

            $table->enum('status', ['Active','Inactive'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
