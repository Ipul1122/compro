<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'title_en', 
        'slug', 
        'category_id', 
        'content', 
        'content_en', 
        'meta_title', 
        'meta_description', 
        'meta_keywords', 
        'published', 
        'image',
        'author_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
