<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'meta_title',
        'meta_description',
        'user_id'
    ];

    // Relasi ke User yang membuat kategori ini
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }    

    // Tambahkan relasi Gallery
    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
}