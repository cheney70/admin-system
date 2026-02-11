<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationLogsTable extends Migration
{
    public function up()
    {
        Schema::create('operation_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->string('username', 50)->comment('用户名');
            $table->string('module', 50)->comment('模块名称');
            $table->string('action', 50)->comment('操作类型');
            $table->string('method', 10)->comment('请求方法');
            $table->string('url', 200)->comment('请求URL');
            $table->text('params')->nullable()->comment('请求参数');
            $table->string('ip', 50)->comment('IP地址');
            $table->text('user_agent')->nullable()->comment('用户代理');
            $table->tinyInteger('status')->default(1)->comment('状态：1成功 0失败');
            $table->string('error_msg')->nullable()->comment('错误信息');
            $table->timestamps();

            $table->index('user_id');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('operation_logs');
    }
}