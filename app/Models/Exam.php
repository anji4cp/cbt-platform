<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'school_id',
        'subject',
        'token',
        'total_questions',
        'duration_minutes',
        'min_submit_minutes',
        'show_score',
        'is_active',
    ];

    public function classes()
    {
        return $this->hasMany(ExamClass::class);
    }
}
