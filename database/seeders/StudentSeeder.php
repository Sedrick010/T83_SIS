<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating 50 students...');
        
        // Common courses and Filipino last names for more realistic data
        $courses = ['BSIT', 'BSCS', 'BSBA', 'BSA', 'BSCE', 'BSEE', 'BSN', 'BSEd', 'BSHRM', 'BSP'];
        $lastNames = [
            'Garcia', 'Santos', 'Reyes', 'Cruz', 'Bautista', 'Ocampo', 'Mendoza', 'Torres', 'Flores', 'Lopez',
            'Villanueva', 'Ramos', 'Rivera', 'Morales', 'Rosales', 'Diaz', 'Castro', 'Gonzales', 'Perez', 'Hernandez',
            'Del Rosario', 'De Guzman', 'Rodriguez', 'Fernandez', 'Dizon', 'Ramirez', 'Navarro', 'Aquino', 'San Jose', 'Aguilar',
            'Valencia', 'Pascual', 'Santiago', 'Espiritu', 'Salvador', 'Marquez', 'Cortez', 'Lim', 'Tan', 'Castillo',
            'Chavez', 'De Leon', 'Abad', 'Miranda', 'Salazar', 'Padilla', 'Sison', 'De La Cruz', 'Mercado', 'Sanchez'
        ];
        
        for ($i = 1; $i <= 50; $i++) {
            // Create user with a unique student number
            $year = rand(2022, 2025); // Year of admission
            $studentNumber = $year . str_pad($i, 6, '0', STR_PAD_LEFT);
            
            // Generate student details
            $firstName = fake()->firstName();
            $lastName = $lastNames[array_rand($lastNames)];
            $middleName = fake()->optional(0.7)->lastName();
            
            // Format name properly
            $fullName = $firstName;
            if ($middleName) {
                $fullName .= ' ' . substr($middleName, 0, 1) . '.';
            }
            $fullName .= ' ' . $lastName;
            
            // Select course and year level
            $course = $courses[array_rand($courses)];
            $yearLevel = rand(1, 4);
            
            // Create the user record
            $user = User::create([
                'name' => $fullName,
                'email' => strtolower(str_replace([' ', '.'], '', $firstName)) . '.' . strtolower($lastName) . '@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => User::ROLE_STUDENT,
                'student_number' => $studentNumber,
                'course' => $course,
                'year_level' => $yearLevel,
                'status' => 'active'
            ]);
            
            // Create corresponding student record
            Student::create([
                'user_id' => $user->id,
                'student_id_number' => $studentNumber,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'middle_name' => $middleName,
                'birth_date' => fake()->dateTimeBetween('-25 years', '-18 years')->format('Y-m-d'),
                'gender' => fake()->randomElement(['male', 'female']),
                'address' => fake()->address(),
                'contact_number' => '09' . fake()->numberBetween(100000000, 999999999),
                'course' => $course,
                'year_level' => $yearLevel,
                'academic_year' => (date('Y')-1) . '-' . date('Y'),
                'semester' => fake()->randomElement(['1st', '2nd', 'Summer']),
                'status' => 'active'
            ]);
            
            if ($i % 10 === 0) {
                $this->command->info("Created {$i} students so far...");
            }
        }
        
        $this->command->info('50 students created successfully!');
    }
}
