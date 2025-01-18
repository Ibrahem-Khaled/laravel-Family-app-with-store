<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;
    protected $fillable = [
        'sub_category_id',
        'user_id',
        'type',
        'title',
        'description',
        'price',
        'colors',
        'sizes',
        'images',
        'quantity',
        'views',
    ];

    protected $casts = [
        'colors' => 'array',
        'sizes' => 'array',
        'images' => 'array',
    ];

    // Define the relationship with the SubCategory model
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
