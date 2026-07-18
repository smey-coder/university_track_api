<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {

            // Remove foreign key first
            $table->dropForeign(['semester_id']);

            // Remove column
            $table->dropColumn('semester_id');

        });
    }


    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {

            $table->foreignId('semester_id')
                ->nullable()
                ->after('academic_year_id')
                ->constrained('semesters')
                ->cascadeOnDelete();

        });
    }

};