<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected static function booted()
    {
        static::creating(function ($user) {
            $user->uuid = Str::uuid(); // استخدام UUID حقيقي
        });
    }
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
        'uuid' => 'string',
    ];

    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class, 'user_subsciptions')
            ->withPivot('active', 'start_at', 'end_at')
            ->withTimestamps();
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
