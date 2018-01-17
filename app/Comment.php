<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Comment extends Model
{
    use Sluggable;

    const IS_PUBLIC = 1;
    const IS_HIDDEN = 0;

    public function post()
    {
        return $this->hasOne(Post::class);
    }

    public function author()
    {
        return $this->hasOne(User::class);
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * Одобрить комментарий
     */
    public function allow() {
        $this->status = Comment::IS_PUBLIC;
        $this->save();
    }

    /**
     * Скрыть комментарий
     */
    public function disallow() {
        $this->status = Comment::IS_HIDDEN;
        $this->save();
    }

    public function toggleStatus()
    {
        if ($this->status == Comment::IS_HIDDEN)
        {
             return $this->allow();
        }

        return $this->disallow();
    }

    public function remove()
    {
        $this->delete();
    }
}
