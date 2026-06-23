<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Department;

class StudentController extends Controller
{
    // GET ALL STUDENTS
    public function index()
    {
        $students = Student::with('department','classes','semester')->get();

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    // GET SINGLE STUDENT
    public function show($id)
    {
        $student = Student::with('department','classes','semester')->find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $student
        ]);
    }
}
