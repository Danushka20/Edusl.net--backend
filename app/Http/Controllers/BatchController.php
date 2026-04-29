<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index()
    {
        return Batch::with('students')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'course_name' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        return Batch::create($data);
    }

    public function show(Batch $batch)
    {
        return $batch->load('students');
    }

    public function update(Request $request, Batch $batch)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'course_name' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $batch->update($data);
        return $batch;
    }

    public function students(Batch $batch)
    {
        return $batch->students()->with(['files', 'certificates'])->get();
    }

    public function destroy(Batch $batch)
    {
        $batch->delete();
        return response()->noContent();
    }
}