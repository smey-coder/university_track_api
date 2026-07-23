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
        Schema::create('assignment_group_members', function (Blueprint $table) {
            $table->id();
            // Assignment Group
            $table->foreignId('assignment_group_id')
                ->constrained('assignment_groups')
                ->cascadeOnDelete();


            // Student
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();


            // Member role in group
            $table->enum('role', [
                'leader',
                'member'
            ])
            ->default('member');


            // Join status
            $table->enum('status', [
                'active',
                'removed'
            ])
            ->default('active');


            $table->timestamp('joined_at')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_group_members');
    }
};
