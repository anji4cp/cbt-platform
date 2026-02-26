<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamPackage extends Model
{
    protected $table = 'exam_packages';

    protected $fillable = [
        'exam_id',
        'package_code',
        'pdf_url',
        'answer_key',
    ];

    protected $casts = [
        'answer_key' => 'array',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
