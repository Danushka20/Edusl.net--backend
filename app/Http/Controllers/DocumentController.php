<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        return Document::with('student')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'type' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx,mp4,avi,mov,jpg,jpeg,png',
            'student_id' => 'required|exists:students,id',
        ]);

        $path = $request->file('file')->store('documents', 'public');

        return Document::create([
            'title' => $request->title,
            'type' => $request->type,
            'file_path' => $path,
            'student_id' => $request->student_id,
        ]);
    }

    public function show(Document $document)
    {
        return $document->load('student');
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'title' => 'required|string',
            'type' => 'required|string',
            'student_id' => 'required|exists:students,id',
        ]);

        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'file|mimes:pdf,doc,docx,mp4,avi,mov,jpg,jpeg,png',
            ]);
            Storage::disk('public')->delete($document->file_path);
            $document->file_path = $request->file('file')->store('documents', 'public');
        }

        $document->fill($request->only(['title', 'type', 'student_id']));
        $document->save();

        return $document->load('student');
    }

    public function destroy(Document $document)
    {
        // Optionally delete the file from storage
        \Storage::disk('public')->delete($document->file_path);
        $document->delete();
        return response()->noContent();
    }
}