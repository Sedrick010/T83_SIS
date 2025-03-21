<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            // Drop the midterm and finals columns
            $table->dropColumn(['midterm', 'finals', 'final_grade']);
            
            // Add a single grade column and subject_id
            $table->decimal('grade', 5, 2)->nullable()->after('enrollment_id');
            $table->foreignId('subject_id')->after('enrollment_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            // Remove the new columns
            $table->dropColumn(['grade', 'subject_id']);
            
            // Restore the original columns
            $table->decimal('midterm', 5, 2)->nullable();
            $table->decimal('finals', 5, 2)->nullable();
            $table->decimal('final_grade', 5, 2)->nullable();
        });
    }
};
