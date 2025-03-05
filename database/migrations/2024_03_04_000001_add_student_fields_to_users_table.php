<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'student_number')) {
                $table->string('student_number')->nullable()->unique();
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('student');
            }
            if (!Schema::hasColumn('users', 'course')) {
                $table->string('course')->nullable();
            }
            if (!Schema::hasColumn('users', 'year_level')) {
                $table->integer('year_level')->nullable();
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'student_number',
                'course',
                'year_level',
                'status'
            ]);
            // Don't drop the role column as it might be used by other parts of the system
        });
    }
}; 