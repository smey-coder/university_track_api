<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    /**
     * GET ALL DEPARTMENTS
     * For Flutter dropdown
     */
    public function index()
    {
        $departments = Department::select(
            'id',
            'department_code',
            'department_name_khmer',
            'department_name_english',
            'description',
            'status'
        )->get();

        return response()->json([
            'success' => true,
            'data' => $departments
        ]);
    }

    /**
     * CREATE NEW DEPARTMENT
     */
    public function store(Request $request)
    {
        $request->validate([
            'department_code' => 'required|string|unique:departments',
            'department_name_khmer' => 'required|string',
            'department_name_english' => 'required|string',
        ]);

        $department = Department::create([
            'department_code' => $request->department_code,
            'department_name_khmer' => $request->department_name_khmer,
            'department_name_english' => $request->department_name_english,
            'description' => $request->description,
            'status' => $request->status ?? 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Department created successfully',
            'data' => $department
        ]);
    }

    /**
     * SHOW SINGLE DEPARTMENT
     */
    public function show(string $id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'Department not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $department
        ]);
    }

    /**
     * UPDATE DEPARTMENT
     */
    public function update(Request $request, string $id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'Department not found'
            ], 404);
        }

        $department->update([
            'department_code' => $request->department_code ?? $department->department_code,
            'department_name_khmer' => $request->department_name_khmer ?? $department->department_name_khmer,
            'department_name_english' => $request->department_name_english ?? $department->department_name_english,
            'description' => $request->description ?? $department->description,
            'status' => $request->status ?? $department->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Department updated successfully',
            'data' => $department
        ]);
    }

    /**
     * DELETE DEPARTMENT
     */
    public function destroy(string $id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'Department not found'
            ], 404);
        }

        $department->delete();

        return response()->json([
            'success' => true,
            'message' => 'Department deleted successfully'
        ]);
    }
}