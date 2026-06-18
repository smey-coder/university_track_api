<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    /**
     * Display all departments
     */
    public function index()
    {
        $departments = Department::latest()->paginate(10);

        return view('departments.index', compact('departments'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store new department
     */
    public function store(Request $request)
    {
        $request->validate([
            'department_code' => 'required|unique:departments',
            'department_name_khmer' => 'required|unique:departments',
            'department_name_english' => 'required|unique:departments',
            'description' => 'nullable|string',
            'status' => 'required',
        ]);

        Department::create([
            'department_code' => $request->department_code,
            'department_name_khmer' => $request->department_name_khmer,
            'department_name_english' => $request->department_name_english,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('departments.index')
            ->with('success', 'Department created successfully');
    }

    /**
     * Show single department
     */
    public function show($id)
    {
        $department = Department::findOrFail($id);

        return view('departments.show', compact('department'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $department = Department::findOrFail($id);

        return view('departments.edit', compact('department'));
    }

    /**
     * Update department
     */
    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $request->validate([
            'department_code' => 'required|unique:departments,department_code,' . $id,
            'department_name_khmer' => 'required|unique:departments,department_name_khmer,' . $id,
            'department_name_english' => 'required|unique:departments,department_name_english,' . $id,
            'description' => 'nullable|string',
            'status' => 'required',
        ]);

        $department->update([
            'department_code' => $request->department_code,
            'department_name_khmer' => $request->department_name_khmer,
            'department_name_english' => $request->department_name_english,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('departments.index')
            ->with('success', 'Department updated successfully');
    }

    /**
     * Delete department
     */
    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully');
    }
}
