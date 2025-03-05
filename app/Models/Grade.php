<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'midterm',
        'finals',
        'remarks'
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function getRemarks(): string
    {
        return $this->finals <= 3.00 ? 'PASSED' : 'FAILED';
    }

    public function getGradeColor(): string
    {
        $grade = $this->finals;
        
        if ($grade === 1.00) return 'bg-green-600';  // Excellent
        if ($grade <= 1.50) return 'bg-green-500';   // Very Good
        if ($grade <= 2.00) return 'bg-green-400';   // Good
        if ($grade <= 3.00) return 'bg-green-300';   // Fair/Passed
        return 'bg-red-500';                         // Failed
    }
}
