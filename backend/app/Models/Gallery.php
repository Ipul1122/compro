<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'user_id',
        'title_image',
        'image',
        'meta_title_image',
        'status'
    ];

    // Relasi balik ke Category (Satu gallery dimiliki oleh satu category)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}