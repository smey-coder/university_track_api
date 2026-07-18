<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentClass; // Change to Class if your model file is named Class.php
use App\Models\Department;
use App\Models\User;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Exception;

class DashboardController extends Controller
{
    /**
     * Display a comprehensive listing of metrics and core database entries.
     * Accessible via GET /api/web/dashboard
     */
    public function index(Request $request)
    {
        try {
            // 1. Gather Metric Aggregations for Overview Cards
            $metrics = [
                'total_students'      => Student::count(),
                'active_students'     => Student::where('status', 'Active')->count(),
                'suspended_students'  => Student::where('status', 'Suspended')->count(),
                'total_classes'       => StudentClass::count(),
                'total_courses'       => Course::count(),
                'total_departments'   => Department::count(),
                'total_system_users'  => User::count(),
                'total_teachers'      => Teacher::count(),
                'total_assignments'   => Assignment::count(),
                'total_assignment_submissions' => AssignmentSubmission::count(),

            ];

            // 2. Handle Search Queries & Filters
            $search = $request->query('search');
            $status = $request->query('status');
            $perPage = $request->query('per_page', 10); // Defaults to 10 entries per page

            // 3. Build a Searchable and Filterable Query Pipeline for Students
            $studentsQuery = Student::with(['department', 'classes']);

            if (!empty($search)) {
                $studentsQuery->where(function ($query) use ($search) {
                    $query->where('student_code', 'LIKE', "%{$search}%")
                          ->orWhere('first_name_english', 'LIKE', "%{$search}%")
                          ->orWhere('last_name_english', 'LIKE', "%{$search}%")
                          ->orWhere('first_name_khmer', 'LIKE', "%{$search}%")
                          ->orWhere('last_name_khmer', 'LIKE', "%{$search}%")
                          ->orWhere('email', 'LIKE', "%{$search}%")
                          ->orWhere('phone', 'LIKE', "%{$search}%");
                });
            }

            if (!empty($status)) {
                $studentsQuery->where('status', $status);
            }

            // Execute Paginated Student Data Query
            $studentsData = $studentsQuery->latest()->paginate($perPage);

            // 4. Fetch Quick-Lookup Lists for Modals/Drop-downs
            $departmentsDropdown = Department::select('id', 'department_name_english')->get();
            $classesDropdown     = StudentClass::select('id', 'class_name')->get();

            // 5. Structure JSON Resource Response
            return response()->json([
                'success' => true,
                'message' => 'Dashboard dataset generated successfully.',
                'data'    => [
                    'metrics'     => $metrics,
                    'departments' => $departmentsDropdown,
                    'classes'     => $classesDropdown,
                    'students'    => [
                        'current_page' => $studentsData->currentPage(),
                        'data'         => $studentsData->items(),
                        'last_page'    => $studentsData->lastPage(),
                        'per_page'     => $studentsData->perPage(),
                        'total'        => $studentsData->total(),
                    ]
                ]
            ], 200);

        } catch (Exception $e) {
            // Graceful Error Catching to prevent raw stack trace exposure
            return response()->json([
                'success' => false,
                'message' => 'An unexpected server-side exception occurred.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /* ==========================================================================
       Unused Resource Stubs (Cleaned or explicitly designated for individual APIs)
       ========================================================================== */

    public function store(Request $request)
    {
        return response()->json(['message' => 'Use dedicated entity endpoints to handle write operations.'], 405);
    }

    public function show(string $id)
    {
        return response()->json(['message' => 'Use dedicated entity endpoints to display singular items.'], 405);
    }

    public function update(Request $request, string $id)
    {
        return response()->json(['message' => 'Use dedicated entity endpoints to execute records mutations.'], 405);
    }

    public function destroy(string $id)
    {
        return response()->json(['message' => 'Use dedicated entity endpoints to purge structural records.'], 405);
    }
}