<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title_image',
        'image',
        'meta_title_image'
    ];

    // Relasi balik ke Category (Satu gallery dimiliki oleh satu category)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}