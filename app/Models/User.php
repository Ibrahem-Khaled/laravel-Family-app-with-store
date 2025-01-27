<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'uuid',
        'api_token',
        'name',
        'username',
        'bio',
        'email',
        'phone',
        'avatar',
        'address',
        'date_of_birth',
        'gender',
        'status',
        'role',
        'password',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class, 'user_subsciptions')
            ->withPivot('active', 'start_at', 'end_at')
            ->withTimestamps();
    }
}
