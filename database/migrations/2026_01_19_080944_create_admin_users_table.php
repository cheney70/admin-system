<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',50)->default('')->comment("¹ÜÀíÕËºÅ");
            $table->string('password',150)->default('')->comment("¹ÜÀíÃÜÂë");
            $table->string('password_salt',10)->default('')->comment("¼ÓÃÜÒò×Ó");
            $table->string('nickname',50)->default('')->comment("êÇ³Æ");
            $table->char('phone',13)->default('')->comment("ÊÖ»úºÅ");
            $table->string('email',150)->default('')->comment("ÓÊÏä");
            $table->string('avatar',150)->default('')->comment("Í·Ïñ");
            $table->mediumtext('introduce',10)->nullable(true)->comment("¼ò½é");
            $table->tinyInteger("status")->default(1)->comment("×´Ì¬:1:ÆôÓÃ,0:½ûÓÃ");
            $table->string('access_token',255)->default('')->comment("µÇÂ¼ÃÜÔ¿");
            $table->string('roleId',50)->default('admin')->comment('½ÇÉ«');
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('admin_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('slug', 50)->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('slug', 50)->unique();
            $table->string('http_method')->nullable();
            $table->text('http_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('admin_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->string('title', 50);
            $table->string('icon', 50);
            $table->string('uri')->nullable();
            $table->string('permission')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('admin_role_users', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('user_id');
            $table->index(['role_id', 'user_id']);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('admin_role_permissions', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('permission_id');
            $table->index(['role_id', 'permission_id']);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('admin_user_permissions', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('permission_id');
            $table->index(['user_id', 'permission_id']);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('admin_role_menus', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('menu_id');
            $table->index(['role_id', 'menu_id']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
        Schema::dropIfExists('admin_roles');
        Schema::dropIfExists('admin_permissions');
        Schema::dropIfExists('admin_menus');
        Schema::dropIfExists('admin_user_permissions');
        Schema::dropIfExists('admin_role_users');
        Schema::dropIfExists('admin_role_permissions');
        Schema::dropIfExists('admin_role_menus');
    }
}
