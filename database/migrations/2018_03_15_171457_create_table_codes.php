<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codes', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type')->comment('赋码类型：0：物品赋码 1：物品位置流转/位置流转 2：物品权利流转 3：聚合 4：拆分 5：孳息 6：单物关联 7：解关联  8：转换');
            $table->char('block_code', 85)->comment('区块链赋码');  
            $table->char('block_hash', 85)->comment('区块hash')->nullable();      
            $table->char('wtx_hash', 85)->comment('交易hash')->nullable();
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
