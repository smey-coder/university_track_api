<?php

namespace App\Http\Controllers;

use App\Models\ClassManager;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Http\Request;

class ClassManagerController extends Controller
{
    public function index()
    {
        $classManagers = ClassManager::with(['student','StudentClass'])
            ->latest()
            ->paginate(10);

        return view('class_managers.index', compact('classManagers'));
    }

    public function create()
    {
        $students = Student::orderBy('first_name_english')->get();
        $classes = StudentClass::orderBy('class_name')->get();

        return view('class_managers.create', compact('students','classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id'=>'required|exists:students,id',
            'class_id'=>'required|exists:classes,id',
            'created_at'=>'required|date',
            // 'status'=>'required'
        ]);

        ClassManager::create($request->all());

        return redirect()
            ->route('class-managers.index')
            ->with('success','Student assigned successfully.');
    }

    public function show(ClassManager $classManager)
    {
        $classManager->load(['student','StudentClass']);

        return view('class_managers.show', compact('classManager'));
    }

    public function edit(ClassManager $classManager)
    {
        $students = Student::orderBy('first_name_english')->get();
        $classes = StudentClass::orderBy('class_name')->get();

        return view('class_managers.edit',
            compact('classManager','students','classes'));
    }

    public function update(Request $request, ClassManager $classManager)
    {
        $request->validate([
            'student_id'=>'required|exists:students,id',
            'class_id'=>'required|exists:classes,id',
            'created_at'=>'required|date',
            // 'status'=>'required'
        ]);

        $classManager->update($request->all());

        return redirect()
            ->route('class_managers.index')
            ->with('success','Assignment updated successfully.');
    }

    public function destroy(ClassManager $classManager)
    {
        $classManager->delete();

        return redirect()
            ->route('class_managers.index')
            ->with('success','Deleted successfully.');
    }
}