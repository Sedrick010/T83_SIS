<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // default password for all users
            'remember_token' => Str::random(10),
            'role' => User::ROLE_STUDENT,
            'status' => 'active',
        ];
    }

    /**
     * Configure the model factory to create a student user.
     *
     * @return static
     */
    public function student(): static
    {
        static $studentCounter = 1;
        
        // Get course and year level
        $courses = ['BSIT', 'BSCS', 'BSBA', 'BSA', 'BSCE', 'BSEE', 'BSN', 'BSEd', 'BSHRM', 'BSP'];
        $course = $this->faker->randomElement($courses);
        $yearLevel = $this->faker->numberBetween(1, 4);
        
        // Generate a truly unique student number using year and incrementing counter
        $year = date('Y');
        $uniqueId = $year . str_pad($studentCounter++, 6, '0', STR_PAD_LEFT);
        
        return $this->state(fn (array $attributes) => [
            'role' => User::ROLE_STUDENT,
            'student_number' => $uniqueId,
            'course' => $course,
            'year_level' => $yearLevel,
            'status' => 'active',
        ]);
    }

    /**
     * Configure the model factory to create an admin user.
     *
     * @return static
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => User::ROLE_ADMIN,
            'student_number' => null,
            'course' => null,
            'year_level' => null,
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
