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
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_code')->unique();

            $table->foreignId('course_id')->constrained('courses','id')->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('teachers','id')->nullOnDelete();

            $table->date('session_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};
