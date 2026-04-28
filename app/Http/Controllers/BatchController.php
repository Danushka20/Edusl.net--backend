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
        $request->validate([
            'name' => 'required|string',
            'course_name' => 'required|string',
        ]);

        return Batch::create($request->all());
    }

    public function show(Batch $batch)
    {
        return $batch->load('students');
    }

    public function update(Request $request, Batch $batch)
    {
        $request->validate([
            'name' => 'required|string',
            'course_name' => 'required|string',
        ]);

        $batch->update($request->all());
        return $batch;
    }

    public function destroy(Batch $batch)
    {
        $batch->delete();
        return response()->noContent();
    }
}