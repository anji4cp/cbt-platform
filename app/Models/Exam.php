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

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function classes()
    {
        return $this->hasMany(ExamClass::class);
    }

    public function packages()
    {
        return $this->hasMany(ExamPackage::class);
    }

    public function sessions()
    {
        return $this->hasMany(ExamSession::class);
    }
}
