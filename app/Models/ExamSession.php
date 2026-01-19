<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    protected $fillable = [
        'exam_id',
        'student_id',
        'exam_package_id',
        'answers',
        'started_at',
        'ends_at',          // 🔥 WAJIB
        'submitted_at',
        'score',
        'device_id',
        'token_verified',   // 🔥 WAJIB
    ];

    protected $casts = [
        'answers'       => 'array',
        'started_at'    => 'datetime',
        'ends_at'       => 'datetime',
        'submitted_at'  => 'datetime',
    ];
    
    public function student()
    {
        return $this->belongsTo(\App\Models\Student::class);
    }

    public function exam()
    {
        return $this->belongsTo(\App\Models\Exam::class);
    }
}