<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return Student::with('batch', 'documents')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:students',
            'batch_id' => 'required|exists:batches,id',
        ]);

        return Student::create($request->all());
    }

    public function show(Student $student)
    {
        return $student->load('batch', 'documents');
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'batch_id' => 'required|exists:batches,id',
        ]);

        $student->update($request->all());
        return $student;
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return response()->noContent();
    }
}