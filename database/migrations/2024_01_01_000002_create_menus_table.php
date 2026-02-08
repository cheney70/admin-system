<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50)->comment('菜单标题');
            $table->string('name', 50)->unique()->comment('菜单名称');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级菜单ID');
            $table->string('path', 100)->nullable()->comment('路由路径');
            $table->string('component', 100)->nullable()->comment('组件路径');
            $table->string('icon', 50)->nullable()->comment('菜单图标');
            $table->tinyInteger('type')->default(1)->comment('类型：1目录 2菜单 3按钮');
            $table->tinyInteger('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态：1显示 0隐藏');
            $table->boolean('is_hidden')->default(false)->comment('是否隐藏');
            $table->boolean('keep_alive')->default(false)->comment('是否缓存');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('menus');
    }
}