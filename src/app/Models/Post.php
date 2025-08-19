<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'category',
        'image_path',
        'rating',
        'tags',
        'comment',
        'user',
    ];


    // ✅ image_path を JSON 配列として扱う
    protected $casts = [
        'image_path' => 'array',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user');
    }
}
