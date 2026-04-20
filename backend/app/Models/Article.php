<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'title_en',
        'slug',
        'content',
        'content_en',
        'image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published',
        'total_view'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}