<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     * title - заголовок поста
     * slug - SEO ссылка
     * content - текст поста
     * category_id - id категории поста
     * user_id - id автора поста
     * status = 0 черновик, 1 опубликован
     * views - просмотры
     * is_featured = 0 не показывать в неромендоавнные, 1 показывать
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->string('content');
            $table->integer('category_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('status')->default(0);
            $table->integer('views')->default(0);
            $table->integer('is_featured')->default();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
