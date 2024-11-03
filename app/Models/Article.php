<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'content', 'author_id', 'category', 'tags'];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}

