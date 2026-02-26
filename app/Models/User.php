<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'school_id',
    ];

    /**
     * Valid roles in system
     */
    public const ROLES = [
        'super_admin',
        'admin_school',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Set default role if not provided
        static::creating(function ($model) {
            if (! $model->role) {
                throw new \InvalidArgumentException('Role must be specified');
            }

            if (! in_array($model->role, self::ROLES)) {
                throw new \InvalidArgumentException('Invalid role: ' . $model->role);
            }
        });
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user is school admin
     */
    public function isSchoolAdmin(): bool
    {
        return $this->role === 'admin_school';
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

}
