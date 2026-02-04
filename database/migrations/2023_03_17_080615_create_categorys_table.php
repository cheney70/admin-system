<?php
//classify
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategorysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name")->default('')->comment("分类名称");
            $table->smallInteger("type")->default(1)->comment("类型，1产品分类，2文章分类");
            $table->smallInteger("level")->default(0)->comment("等级：1一级，2二级，3三级");
            $table->bigInteger("parent_id")->default(0)->comment("父id");
            $table->string("sign")->default('')->comment("类型标识");
            $table->string("icon")->default('')->comment("分类图标");
            $table->string("remarks")->default('')->comment("分类描述");
            $table->tinyInteger("status")->default(0)->comment("状态:1:启用,0:禁用");
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
        Schema::dropIfExists('categorys');
    }
}
