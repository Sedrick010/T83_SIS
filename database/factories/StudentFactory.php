<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $idCounter = 1;
        
        // Get current academic year (e.g., 2023-2024)
        $year = date('Y');
        $academicYear = ($year - 1) . '-' . $year;
        
        // Generate a random but realistic student ID number using counter for uniqueness
        $idPrefix = substr($year, -2);
        $idSuffix = str_pad($idCounter++, 6, '0', STR_PAD_LEFT);
        $studentIdNumber = $idPrefix . $idSuffix;
        
        // Generate course from common college courses
        $courses = ['BSIT', 'BSCS', 'BSBA', 'BSA', 'BSCE', 'BSEE', 'BSN', 'BSEd', 'BSHRM', 'BSP'];
        $course = $this->faker->randomElement($courses);
        
        // Generate year level (1-4)
        $yearLevel = $this->faker->numberBetween(1, 4);
        
        return [
            'user_id' => User::factory(),
            'student_id_number' => $studentIdNumber,
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'middle_name' => $this->faker->optional(0.7)->lastName(), // 70% chance to have a middle name
            'birth_date' => $this->faker->dateTimeBetween('-25 years', '-18 years')->format('Y-m-d'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'address' => $this->faker->address(),
            'contact_number' => '09' . $this->faker->numberBetween(100000000, 999999999),
            'course' => $course,
            'year_level' => $yearLevel,
            'academic_year' => $academicYear,
            'semester' => $this->faker->randomElement(['1st', '2nd', 'Summer']),
            'status' => 'active',
        ];
    }
} 