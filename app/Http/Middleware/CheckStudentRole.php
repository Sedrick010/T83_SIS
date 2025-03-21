<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStudentRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->role !== 'student') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'You are not authorized as a student.'], 403);
            }
            
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized as a student. Please contact the administrator.');
        }

        // Check if the user has a student record
        if (!$request->user()->student) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your student profile is not complete.'], 403);
            }
            
            return redirect()->route('dashboard')
                ->with('error', 'Your student profile is not complete. Please contact the administrator.');
        }

        return $next($request);
    }
}