<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function category()
    {
        return $this->hasOne(Category::class);
    }

    public function author()
    {
        return $this->hasOne(User::class);
    }

    /**
     * Tag - модель с которой связываемся
     * post_tag - таблица для связи многое-ко-многим
     * post_id - id этой модели в post_tag
     * tag_id - id модели Tag
     */
    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'posts_tags',
            'post_id',
            'tag_id'
        );
    }
}
