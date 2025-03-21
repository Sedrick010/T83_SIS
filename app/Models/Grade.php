<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'enrollment_id',
        'subject_id',
        'grade',
        'remarks'
    ];

    // Valid grade values in the 5.00-1.00 system
    public static $validGrades = [
        1.00, 1.25, 1.50, 1.75,
        2.00, 2.25, 2.50, 2.75,
        3.00, 3.25, 3.50, 3.75,
        4.00, 4.25, 4.50, 4.75,
        5.00, 'INC'
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function getRemarks(): string
    {
        // Handle INC grade
        if ($this->grade === 'INC') {
            return 'INCOMPLETE';
        }
        
        // 3.00 is the passing grade in the 5.00-1.00 system
        return $this->grade <= 3.00 ? 'PASSED' : 'FAILED';
    }

    public function getGradeColor(): string
    {
        $grade = $this->grade;
        
        // Handle INC grade
        if ($grade === 'INC') return 'bg-yellow-500';   // INC (Incomplete)
        
        if ($grade <= 1.25) return 'bg-green-600';      // Excellent (1.00-1.25)
        if ($grade <= 1.75) return 'bg-green-500';      // Very Good (1.50-1.75)
        if ($grade <= 2.25) return 'bg-green-400';      // Good (2.00-2.25)
        if ($grade <= 3.00) return 'bg-green-300';      
        return 'bg-red-500';                            // Failed (3.25-5.00)
    }

    public static function isValidGrade($grade): bool
    {
        if ($grade === 'INC') {
            return true;
        }
        
        // Convert to float only for numeric grades
        $numericGrade = is_numeric($grade) ? (float) $grade : null;
        return in_array($numericGrade, self::$validGrades, true);
    }
}
