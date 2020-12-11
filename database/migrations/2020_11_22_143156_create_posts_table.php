<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title',80);
            $table->text('post');
            $table->text('jsonData')->nullable();
            $table->string('postExcerpt');
            $table->string('slug')->unique()->default('');
            $table->unsignedBigInteger('user_id');
            $table->string('featuredImage')->nullable();
            $table->string('metaDescription',300);
            $table->unsignedInteger('views')->default(0);
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
