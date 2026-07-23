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
            // Teacher who graded
            $table->foreignId('graded_by')
                ->nullable()
                ->after('feedback')
                ->constrained('teachers')
                ->nullOnDelete();

            // Date and time graded
            $table->timestamp('graded_at')
                ->nullable()
                ->after('graded_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignment_submissions', function (Blueprint $table) {
            $table->dropForeign(['graded_by']);

            $table->dropColumn([
                'graded_by',
                'graded_at'
            ]);
        });
    }
};
