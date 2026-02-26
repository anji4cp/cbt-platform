<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamClass extends Model
{
    protected $fillable = [
        'exam_id',
        'class_name',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
