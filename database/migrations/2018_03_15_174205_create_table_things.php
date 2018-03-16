<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableThings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('things', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sn',18)->comment('物品条码');
            $table->string('title',100)->comment('物品名称');  
            $table->string('alias',100)->comment('别名');  
            $table->string('unspsc',8)->comment('邓白氏编码');  
            $table->string('brand_name',100)->comment('品牌名称');  
            $table->string('specifications',20)->comment('物品规格');  
            $table->char('data_hash',32)->comment('物品信息hash，唯一属性');   
            $table->double('latitude')->comment('纬度'); 
            $table->double('longitude')->comment('经度'); 
            $table->text('ext_info')->comment('扩展字段'); 
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
