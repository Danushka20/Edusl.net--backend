<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function index(Student $student)
    {
        return $student->certificates()->with('batch')->get();
    }

    public function store(Request $request, Student $student)
    {
        $data = $request->validate([
            'certificate_name' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png',
            'issued_date' => 'nullable|date',
        ]);

        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store('certificates', 'public');

        $shareToken = Str::random(64);
        while (Certificate::where('share_token', $shareToken)->exists()) {
            $shareToken = Str::random(64);
        }

        $certificate = Certificate::create([
            'student_id' => $student->id,
            'batch_id' => $student->batch_id,
            'certificate_name' => $data['certificate_name'],
            'file_path' => $path,
            'share_token' => $shareToken,
            'issued_date' => $data['issued_date'] ?? now()->toDateString(),
        ]);

        return response()->json($certificate, 201);
    }

    public function share(string $shareToken)
    {
        $certificate = Certificate::with(['student', 'batch'])
            ->where('share_token', $shareToken)
            ->first();

        if (! $certificate) {
            return response()->json(['message' => 'Certificate not found.'], 404);
        }

        return response()->json([
            'id' => $certificate->id,
            'student' => $certificate->student?->only(['id', 'name', 'email']),
            'batch' => $certificate->batch?->only(['id', 'name', 'description', 'start_date', 'end_date']),
            'certificate_name' => $certificate->certificate_name,
            'issued_date' => $certificate->issued_date?->toDateString(),
            'file_url' => Storage::disk('public')->url($certificate->file_path),
        ]);
    }
}
