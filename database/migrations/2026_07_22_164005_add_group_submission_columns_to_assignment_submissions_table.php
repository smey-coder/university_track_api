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
        Schema::table('assignment_submissions', function (Blueprint $table) {
            // Group Submission
            $table->foreignId('group_id')
                ->nullable()
                ->after('assignment_id')
                ->constrained('assignment_groups')
                ->nullOnDelete();

            // Student who uploaded the file
            $table->foreignId('submitted_by')
                ->nullable()
                ->after('student_id')
                ->constrained('students')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignment_submissions', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn('group_id');

            $table->dropForeign(['submitted_by']);
            $table->dropColumn('submitted_by');
        });
    }
};
