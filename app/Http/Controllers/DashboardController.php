<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Department;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Show Dashboard
     */
    public function index()
    {
        // Get real statistics from database
        $totalStudents = Student::count();
        $totalDepartments = Department::count();
        $totalUsers = User::count();

        // Latest students (for recent activity UI)
        $latestStudents = Student::latest()->take(5)->get();

        return view('admin.dashboard', [
            'totalStudents' => $totalStudents,
            'totalDepartments' => $totalDepartments,
            'totalUsers' => $totalUsers,
            'latestStudents' => $latestStudents
        ]);
    }
}
