<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use Faker\Factory as Faker;

class StudentsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        // Create 10 students with fake names and emails
        foreach (range(1, 10) as $index) {
            Student::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
            ]);
        }
    }
}
