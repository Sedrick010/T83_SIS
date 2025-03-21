<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'units'
    ];

    public function enrollments(): BelongsToMany
    {
        return $this->belongsToMany(Enrollment::class, 'enrollment_subjects')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollment_subjects', 'subject_id', 'user_id')
                    ->using(EnrollmentSubject::class)
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function prerequisites(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_prerequisites', 'subject_id', 'prerequisite_id')
                    ->withTimestamps();
    }

    public function prerequisiteFor(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_prerequisites', 'prerequisite_id', 'subject_id')
                    ->withTimestamps();
    }

    public function getStudentsCountAttribute(): int
    {
        return $this->enrollments()
            ->join('users', 'enrollments.user_id', '=', 'users.id')
            ->where('users.role', 'student')
            ->count();
    }
}
