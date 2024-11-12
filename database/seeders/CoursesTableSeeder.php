<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CoursesTableSeeder extends Seeder
{
    public function run()
    {
        // Create 5 courses
        $courses = ['Math', 'English', 'Science', 'History', 'Geography'];

        foreach ($courses as $course) {
            Course::create([
                'title' => $course,
            ]);
        }
    }
}

