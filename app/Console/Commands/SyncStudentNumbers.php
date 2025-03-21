<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Models\User;
use Illuminate\Console\Command;

class SyncStudentNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-student-numbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync student numbers from students table to users table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to sync student numbers...');
        
        // Get all users with role 'student' that have empty student_number
        $users = User::where('role', User::ROLE_STUDENT)
                    ->whereNull('student_number')
                    ->orWhere('student_number', '')
                    ->get();
        
        $count = 0;
        
        foreach ($users as $user) {
            // Find the student record for this user
            $student = Student::where('user_id', $user->id)->first();
            
            if ($student && !empty($student->student_id_number)) {
                // Update the user's student_number
                $user->update(['student_number' => $student->student_id_number]);
                $count++;
            }
        }
        
        $this->info("Synced {$count} student numbers successfully.");
        
        return Command::SUCCESS;
    }
}
