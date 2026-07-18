<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('subject_schedules', function (Blueprint $table) {

            $table->foreignId('academic_year_id')
                  ->nullable()
                  ->after('semester_id');

        });
    }


    public function down(): void
    {
        Schema::table('subject_schedules', function (Blueprint $table) {

            $table->dropColumn('academic_year_id');

        });
    }

};