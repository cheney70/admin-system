<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique()->comment('权限名称');
            $table->string('code', 100)->unique()->comment('权限编码');
            $table->string('description')->nullable()->comment('权限描述');
            $table->unsignedBigInteger('menu_id')->nullable()->comment('关联菜单ID');
            $table->tinyInteger('type')->default(1)->comment('类型：1菜单权限 2操作权限');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}