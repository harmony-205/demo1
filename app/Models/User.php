<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'so_dien_thoai', 'trang_thai'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_TEACHER = 'teacher';

    public const ROLES = [
        self::ROLE_ADMIN => 'Quản trị viên',
        self::ROLE_TEACHER => 'Giảng viên',
    ];

    /**
     * Check if user is Admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is Teacher
     */
    public function isTeacher(): bool
    {
        return $this->role === self::ROLE_TEACHER;
    }

    /**
     * Get human-readable role name
     */
    public function getRoleNameAttribute(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

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
            'trang_thai' => 'boolean',
        ];
    }
}
