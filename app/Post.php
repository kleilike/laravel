<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use Sluggable;

    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;
    const IS_STANDART = 0;
    const IS_FEATURES = 1;

    protected $fillable = ['title', 'content', 'user_id'];

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

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    static public function add($fields) {
        $post = new static;
        $post->fill($fields);
        $post->user_id = 1;
        $post->save();

        return $post;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    public function remove()
    {
        Storage::delete('upload/' . $this->image);
        $this->delete();
    }

    public function uploadImage($image)
    {
        if ($image == null) { return; }

        Storage::delete('upload/' . $this->image);
        $filename = str_random(10) . '.' . $image->extension();
        $image->saveAs('upload', $filename);
        $this->image = $filename;
        $this->save();
    }

    /**
     * Вернуть картину
     * @return string
     */
    public function getImage()
    {
        if ($this->image == null) {
            return '/img/no-image.png';
        }

        return '/upload' . $this->image;
    }

    /**
     * Добавить категорию
     * @param $id - id категории
     */
    public function serCategory($id)
    {
        if ($id == null) { return; }

        $this->category_id = $id;
        $this->save();

//        $category = Category::find($id);
//        $this->category()->save($category);
//        $this->save();
    }

    /**
     * Добавить теги
     * @param $ids - массив тегов
     */
    public function setTags($ids)
    {
        if ($ids == null) { return; }

        $this->tags()->sync($ids);
        $this->save();
    }

    /**
     * Перенести статью в черновик
     */
    public function setDraft()
    {
        $this->status = Post::IS_DRAFT;
        $this->save();
    }

    /**
     * Опубликовать статью
     */
    public function setPublic()
    {
        $this->satus = Post::IS_PUBLIC;
    }

    /**
     * Перекюлчатель публикации стратьи
     * @param $value
     */
    public function toggleStatus($value)
    {
        if ($value == null) {
            return $this->setDraft();
        }

        return $this->setPublic();
    }

    /**
     * Рекомендовать статью
     */
    public function setFeatured()
    {
        $this->is_featured = Post::IS_FEATURES;
        $this->save();
    }

    /**
     * убрать из рекомендаций
     */
    public function setStandart()
    {
        $this->is_featured = Post::IS_STANDART;
    }

    /**
     * Перекюлчатель рекомендации стратьи
     * @param $value
     */
    public function toggleFeatured($value)
    {
        if ($value == null) {
            return $this->setStandart();
        }

        return $this->setFeatured();
    }

    /**
     * Добавить категорию
     * @param $id - id новой категории
     */
    public function setCategory($id)
    {
        if ($id == null) { return; }

        $this->category_id = $id;
        $this->save();
    }
}
