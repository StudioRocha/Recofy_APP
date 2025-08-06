<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'image_path',
        'rating',
        'tags',
        'comment',
    ];


    // ✅ image_path を JSON 配列として扱う
    protected $casts = [
        'image_path' => 'array',
    ];
}
