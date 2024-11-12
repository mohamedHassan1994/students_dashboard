<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Course;

class GradesTableSeeder extends Seeder
{
    public function run()
    {
        // Attach grades and attendance to students and courses
        $students = Student::all();
        $courses = Course::all();

        foreach ($students as $student) {
            foreach ($courses as $course) {
                Grade::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'grade' => rand(50, 100), // random grade between 50 and 100
                    'attended' => rand(0, 1), // random attendance (0 = Absent, 1 = Present)
                ]);
            }
        }
    }
}
