<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return Student::with(['batch', 'files', 'certificates'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:students',
            'batch_id' => 'required|exists:batches,id',
            'phone' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        return Student::create($data);
    }

    public function show(Student $student)
    {
        return $student->load(['batch', 'files', 'certificates']);
    }

    public function storeForBatch(Request $request, Batch $batch)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:students',
            'phone' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $data['batch_id'] = $batch->id;

        return Student::create($data);
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'batch_id' => 'required|exists:batches,id',
            'phone' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $student->update($data);
        return $student;
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return response()->noContent();
    }
}