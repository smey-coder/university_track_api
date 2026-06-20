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
        Schema::table('classes', function (Blueprint $table) {

            // ✅ ADD DEPARTMENT
            $table->foreignId('department_id')
                ->nullable()
                ->after('academic_year_id')
                ->constrained()
                ->cascadeOnDelete();

            // ✅ ADD SEMESTER
            $table->foreignId('semester_id')
                ->nullable()
                ->after('department_id')
                ->constrained()
                ->cascadeOnDelete();

            // OPTIONAL (useful for API)
            $table->integer('max_students')->default(40)->after('room');

            $table->boolean('status')->default(1)->after('max_students');
        });
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['semester_id']);

            $table->dropColumn([
                'department_id',
                'semester_id',
                'max_students',
                'status'
            ]);
        });
    }
};
