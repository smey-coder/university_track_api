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
        Schema::create('assignment_groups', function (Blueprint $table) {
            $table->id();
            // Assignment reference
            $table->foreignId('assignment_id')
                ->constrained('assignments')
                ->cascadeOnDelete();


            // Group name
            $table->string('group_name');


            // Group leader
            $table->foreignId('leader_student_id')
                ->constrained('students')
                ->cascadeOnDelete();


            // Who created this group
            $table->enum('created_by', [
                'teacher',
                'student'
            ])
            ->default('teacher');


            // Maximum student in group
            $table->integer('max_members')
                ->default(5);


            // Group status
            $table->enum('status', [
                'active',
                'inactive'
            ])
            ->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_groups');
    }
};
