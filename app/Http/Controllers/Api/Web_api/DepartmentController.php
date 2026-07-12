<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     */
    public function index(Request $request)
    {
        try {

            $perPage = $request->get('per_page', 10);

            $departments = Department::withCount([
                'students',
                'courses',
                'classes'
            ])
            ->latest()
            ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Departments retrieved successfully.',
                'data' => $departments,
                'pagination' => [
                'total'        => $departments->total(),
                'count'        => $departments->count(),
                'per_page'     => $departments->perPage(),
                'current_page' => $departments->currentPage(),
                'total_pages'  => $departments->lastPage()
                ]
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve departments.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'department_code' => 'required|string|max:20|unique:departments,department_code',
                'department_name_khmer' => 'required|string|max:255',
                'department_name_english' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|in:Active,Inactive',
            ]);

            if ($validator->fails()) {

                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error.',
                    'errors' => $validator->errors()
                ], 422);

            }

            $department = Department::create($request->only([
                'department_code',
                'department_name_khmer',
                'department_name_english',
                'description',
                'status'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Department created successfully.',
                'data' => $department
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to create department.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified department.
     */
    public function show(string $id)
    {
        try {

            $department = Department::with([
                'students',
                'courses',
                'classes'
            ])
            ->withCount([
                'students',
                'courses',
                'classes'
            ])
            ->find($id);

            if (!$department) {

                return response()->json([
                    'success' => false,
                    'message' => 'Department not found.'
                ], 404);

            }

            return response()->json([
                'success' => true,
                'message' => 'Department retrieved successfully.',
                'data' => $department
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve department.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified department.
     */
    public function update(Request $request, string $id)
    {
        try {

            $department = Department::find($id);

            if (!$department) {

                return response()->json([
                    'success' => false,
                    'message' => 'Department not found.'
                ], 404);

            }

            $validator = Validator::make($request->all(), [
                'department_code' => 'required|string|max:20|unique:departments,department_code,' . $id,
                'department_name_khmer' => 'required|string|max:255',
                'department_name_english' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|in:Active,Inactive',
            ]);

            if ($validator->fails()) {

                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error.',
                    'errors' => $validator->errors()
                ], 422);

            }

            $department->update($request->only([
                'department_code',
                'department_name_khmer',
                'department_name_english',
                'description',
                'status'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Department updated successfully.',
                'data' => $department
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to update department.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified department.
     */
    public function destroy(string $id)
    {
        try {

            $department = Department::find($id);

            if (!$department) {

                return response()->json([
                    'success' => false,
                    'message' => 'Department not found.'
                ], 404);

            }

            $department->delete();

            return response()->json([
                'success' => true,
                'message' => 'Department deleted successfully.'
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete department.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}