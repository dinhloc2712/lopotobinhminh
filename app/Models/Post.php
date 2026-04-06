<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'category_id', 'title', 'slug', 'summary', 'thumbnail', 'status',
        'meta_title', 'meta_description', 'meta_keywords'
    ];

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    public function blocks()
    {
        return $this->hasMany(PostBlock::class)->orderBy('order');
    }
}
