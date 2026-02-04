<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("type_id")->default(0)->comment("文章分类id");
            $table->string("title")->default('')->comment("文章分类");
            $table->string("photo")->default('')->comment("文章首图");
            $table->string("source")->default('')->comment("出处");//出处
            $table->string("author")->default('')->comment("作者");//作者
            $table->tinyInteger("is_top")->default(0)->comment("是否置顶");
            $table->string("keywords",255)->comment("关键字")->nullable();
            $table->longText("content")->comment("文章内容")->nullable();
            $table->tinyinteger("status")->defalut(0)->comment("状态");
            $table->timestamps();
            $table->softDeletes();
            $table->index("type_id","articles_table_type_id_index");
            $table->index("title","articles_table_title_index");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
