<?php

namespace App\Http\Controllers;

use App\Models\ClassManager;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassManagerController extends Controller
{
    public function index()
    {
        try{
            $classManagers = ClassManager::with(['student','StudentClass'])
            ->latest()
            ->paginate(10);


            return view('class_managers.index', compact('classManagers'));
        }catch(\Exception $e){

            return back()->with(
                'error',
                'Class manager not found or failed to load. ' . $e->getMessage()
            );
        }
    }

    public function create()
    {
        $students = Student::orderBy('first_name_english')->get();
        $classes = StudentClass::orderBy('class_name')->get();

        return view('class_managers.create', compact('students','classes'));
    }

    public function store(Request $request)
    {
        try{
            $request->validate([
            'student_id' => 'required|exists:students,id|unique:class_managers,student_id',
            'class_id'   => 'required|exists:classes,id',
            'created_at' => 'required|date',
        ]);

        DB::transaction(function () use ($request) {

            // Save class assignment history
            ClassManager::create([
                'student_id' => $request->student_id,
                'class_id'   => $request->class_id,
                'created_at' => $request->created_at,
            ]);

            // Update student's current class
            Student::where('id', $request->student_id)
                ->update([
                    'class_id' => $request->class_id,
                ]);

        });

        return redirect()
            ->route('class-managers.index')
            ->with('success', 'Student assigned to class successfully.');
        }catch(\Exception $e){

            return back()->with(
                'error',
                'Class manager not found or failed to create. ' . $e->getMessage()
            );
        }
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
            'student_id' => 'required|exists:students,id',
            'class_id'   => 'required|exists:classes,id',
            'created_at' => 'required|date',
        ]);

        DB::transaction(function () use ($request, $classManager) {

            $classManager->update([
                'student_id' => $request->student_id,
                'class_id'   => $request->class_id,
                'created_at' => $request->created_at,
            ]);

            Student::where('id', $request->student_id)
                ->update([
                    'class_id' => $request->class_id,
                ]);

        });

        return redirect()
            ->route('class-managers.index')
            ->with('success', 'Student class updated successfully.');
    }

    public function destroy(ClassManager $classManager)
    {
        $classManager->delete();

        return redirect()
            ->route('class-managers.index')
            ->with('success','Deleted successfully.');
    }
}