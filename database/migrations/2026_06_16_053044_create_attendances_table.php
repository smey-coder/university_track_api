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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('attendance_code')->unique();

            $table->foreignId('session_id')->constrained('attendance_sessions','id')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students','id')->cascadeOnDelete();

            $table->enum('status', ['Present','Absent','Late','Excused'])->default('Absent');
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
