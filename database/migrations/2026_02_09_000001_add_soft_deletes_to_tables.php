<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToTables extends Migration
{
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('operation_logs', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('operation_logs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
