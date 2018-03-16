<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type')->comment('类型： 1 打包  2 拆分 3 转化 4 孳息 5 位置流转');
            $table->integer('tc_id')->comment('对应物品区块链赋码表的ID');  
            $table->integer('thing_id')->comment('物品表id');  
            $table->integer('code_id')->comment('赋码表id'); 
            $table->string('uploader_id',64)->comment('数据上报人在商户系统中的唯一标识')->nullable(); 
            $table->string('uploader',20)->comment('数据上报人姓名')->nullable(); 
            $table->string('status',20)->comment('记录在商户系统中的状态码')->nullable(); 
            $table->string('note',255)->comment('记录在商户系统中的状态码对应的描述信息')->nullable(); 
            $table->double('latitude')->comment('纬度'); 
            $table->double('longitude')->comment('经度'); 
            $table->text('ext_info')->comment('扩展字段')->nullable(); 
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
