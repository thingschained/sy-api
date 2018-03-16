<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableThingsCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('things_codes', function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('tid',64)->comment('物品在商户系统的唯一标识');
                    $table->string('sn',18)->comment('物品条码');
                    $table->integer('thing_id')->comment('物品表id');  
                    $table->integer('code_id')->comment('赋码表id'); 
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
