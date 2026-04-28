<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'type', 'file_path', 'student_id'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}