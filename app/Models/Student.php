<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    protected $guard = 'student';

    protected $fillable = [
    'school_id',
    'name',
    'username',
    'password',
    'exam_password',
    'class',
    'is_active',
];

    protected $hidden = ['password'];
}
