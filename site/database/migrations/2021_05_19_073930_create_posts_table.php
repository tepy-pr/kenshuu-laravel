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
            $table->increments("post_id");
            $table->string("title");
            $table->string("body");
            $table->string("thumbnail");
            $table->unsignedInteger("user_id");
            $table->date("created_at")->useCurrent();
            $table->date("updated_at")->useCurrentOnUpdate();
            $table->foreign("user_id")->references("user_id")->on("users")->onDelete("cascade");
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
