<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;

class TeacherReportController extends Controller
{
    /**
     * Print All Teachers
     */
    public function teacherListReport()
    {
        $teachers = Teacher::with('department')
            ->orderBy('teacher_code')
            ->get();

        return view(
            'reports.teacher-list',
            compact('teachers')
        );
    }

    /**
     * Print One Teacher
     */
    public function teacherProfileReport($id)
    {
        $teacher = Teacher::with('department')
            ->findOrFail($id);

        return view(
            'reports.teacher-profile',
            compact('teacher')
        );
    }
}