<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_id',
        'status',
        'trial_ends_at',
        'subscription_ends_at',
        'logo',
        'theme_color',
    ];


    public function getCurrentStatusAttribute()
    {
        $now = now();

        if ($this->status === 'trial') {
            if ($this->trial_ends_at && $now->lessThanOrEqualTo($this->trial_ends_at)) {
                return 'trial';
            }
            return 'expired';
        }

        if ($this->status === 'active') {
            if ($this->subscription_ends_at && $now->lessThanOrEqualTo($this->subscription_ends_at)) {
                return 'active';
            }
            return 'expired';
        }

        return 'expired';
    }

    public function getExpiredAtAttribute()
    {
        if ($this->status === 'trial') {
            return $this->trial_ends_at;
        }

        if ($this->status === 'active') {
            return $this->subscription_ends_at;
        }

        return null;
    }

    public function admins()
    {
        return $this->hasMany(User::class)->where('role', 'admin_school');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }


}