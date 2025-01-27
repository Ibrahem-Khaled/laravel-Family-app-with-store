<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'features',
        'price',
        'duration',
        'max_users',
        'max_families',
        'max_posts',
        'payment_url',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_subsciptions')
            ->withPivot('active', 'start_at', 'end_at')
            ->withTimestamps();
    }
}
