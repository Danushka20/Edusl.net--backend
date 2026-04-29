<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentFileController extends Controller
{
    public function index(Student $student)
    {
        return $student->files()->get();
    }

    public function store(Request $request, Student $student)
    {
        $data = $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,mp4,avi,mov',
            'file_type' => 'required|string',
        ]);

        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store('student-files', 'public');

        $file = File::create([
            'student_id' => $student->id,
            'file_name' => $uploadedFile->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $data['file_type'],
            'uploaded_by' => Auth::id(),
        ]);

        return response()->json($file, 201);
    }
}
