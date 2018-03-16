<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePackages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type')->comment('类型： 1 打包  2 拆分 3 转化 3 孳息');
            $table->integer('from_tc_id')->comment('原始物品对象id');  
            $table->integer('to_tc_id')->comment('生成的物品对象id');      
            $table->timestamp('updated_at');  
            $table->timestamp('created_at')->useCurrent();
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
        //
    }
}
