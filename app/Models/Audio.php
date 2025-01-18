<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    use HasFactory;
    protected $fillable = [
        'sub_category_id',
        'user_id',
        'title',
        'file',
        'duration',
        'description',
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
