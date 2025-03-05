<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            [
                'code' => 'MATH101',
                'name' => 'Basic Mathematics',
                'description' => 'Introduction to basic mathematical concepts',
                'units' => 3,
            ],
            [
                'code' => 'ENG101',
                'name' => 'English Composition',
                'description' => 'Basic writing and composition skills',
                'units' => 3,
            ],
            [
                'code' => 'COMP101',
                'name' => 'Introduction to Computing',
                'description' => 'Basic concepts of computer science',
                'units' => 3,
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
} 