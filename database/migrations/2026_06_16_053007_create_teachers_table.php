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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('teacher_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users','id')->nullOnDelete();
            $table->foreignId('department_id')->constrained('departments','id')->cascadeOnDelete();

            $table->string('first_name_khmer');
            $table->string('last_name_khmer');
            $table->string('first_name_english');
            $table->string('last_name_english');
            $table->enum('gender', ['Male','Female']);
            $table->date('date_of_birth')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('photo')->nullable();
            $table->date('hire_date')->nullable();
            $table->enum('status', ['Active','Inactive'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
