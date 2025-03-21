<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StudentRegistrationNotification extends Notification
{
    use Queueable;

    private $studentData;

    public function __construct($studentData)
    {
        $this->studentData = $studentData;
    }

    public function via($notifiable): array
    {
        return ['database']; // Only using database notifications
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Student Registration',
            'message' => 'You have been registered as a student.',
            'student_id' => $this->studentData['student_id_number'],
            'course' => $this->studentData['course'],
            'year_level' => $this->studentData['year_level'],
            'time' => now()->toDateTimeString()
        ];
    }
} 