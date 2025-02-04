<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::random(11); // إنشاء uuid عشوائي مكون من 11 حرف/رقم
        });
    }

    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class, 'user_subscriptions')
            ->withPivot('active', 'start_at', 'end_at')
            ->withTimestamps();
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}