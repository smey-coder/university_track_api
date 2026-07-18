<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Teacher;
use App\Models\Course;

class CourseController extends Controller
{
    /**
     * Display all courses
     */
    public function index()
    {
        $courses = Course::with(['department', 'teacher'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $courses->items(),
            'pagination' => [
                'current_page' => $courses->currentPage(),
                'last_page' => $courses->lastPage(),
                'per_page' => $courses->perPage(),
                'total' => $courses->total(),
            ]
        ]);
    }

    /**
     * Load Department & Teacher for Create/Update Form
     */
    public function getFormDataDependencies()
    {
        $departments = Department::select(
                'id',
                'department_name_english'
            )
            ->where('status', 'Active')
            ->orderBy('department_name_english')
            ->get();

        $teachers = Teacher::select(
                'id',
                'first_name_english',
                'last_name_english'
            )
            ->where('status', 'Active')
            ->orderBy('first_name_english')
            ->get();

        return response()->json([
            'success' => true,
            'departments' => $departments,
            'teachers' => $teachers,
        ]);
    }

    /**
     * Store Course
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_code'   => 'required|string|max:50|unique:courses,course_code',
            'department_id' => 'required|exists:departments,id',
            'teacher_id'    => 'required|exists:teachers,id',
            'course_name'   => 'required|string|max:255|unique:courses,course_name',
            'credits'       => 'required|integer|min:1',
            'description'   => 'nullable|string',
            'status'        => 'required|in:Active,Inactive',
        ]);

        $course = Course::create([
            'course_code'   => $request->course_code,
            'department_id' => $request->department_id,
            'teacher_id'    => $request->teacher_id,
            'course_name'   => $request->course_name,
            'credits'       => $request->credits,
            'description'   => $request->description,
            'status'        => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Course created successfully.',
            'data' => $course->load(['department', 'teacher']),
        ], 201);
    }

    /**
     * Show Course
     */
    public function show($id)
    {
        $course = Course::with(['department', 'teacher'])->find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $course,
        ]);
    }

    /**
     * Update Course
     */
    public function update(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found.',
            ], 404);
        }

        $request->validate([
            'course_code'   => 'required|string|max:50|unique:courses,course_code,' . $course->id,
            'department_id' => 'required|exists:departments,id',
            'teacher_id'    => 'required|exists:teachers,id',
            'course_name'   => 'required|string|max:255|unique:courses,course_name,' . $course->id,
            'credits'       => 'required|integer|min:1',
            'description'   => 'nullable|string',
            'status'        => 'required|in:Active,Inactive',
        ]);

        $course->update([
            'course_code'   => $request->course_code,
            'department_id' => $request->department_id,
            'teacher_id'    => $request->teacher_id,
            'course_name'   => $request->course_name,
            'credits'       => $request->credits,
            'description'   => $request->description,
            'status'        => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Course updated successfully.',
            'data' => $course->load(['department', 'teacher']),
        ]);
    }

    /**
     * Delete Course
     */
    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found.',
            ], 404);
        }

        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Course deleted successfully.',
        ]);
    }
    /**
 * Course Dropdown API
 */
public function dropdown()
{
    try {

        $courses = Course::select(
                'id',
                'course_code',
                'course_name',
                'credits'
            )
            ->where('status', 'Active')
            ->orderBy('course_name')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Courses loaded successfully.',
            'data' => $courses
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => 'Failed to load courses.',
            'error' => $e->getMessage()
        ], 500);

    }
}
}