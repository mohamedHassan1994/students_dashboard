<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\Course;

class ReportController extends Controller
{
    public function index()
    {
        // Fetch students with their courses and grades
        $students = Student::with(['courses'])->paginate(10);
        $courses = Course::all();

        return view('reports.index', compact('students', 'courses'));
    }
}