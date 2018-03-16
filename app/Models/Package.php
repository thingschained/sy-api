<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 打包模型
 *
 * @author jerry<cuiliang.hu@thingschained.com>
 */
class Package extends Model
{

	 /**
    *  生成物品打包对象
    * @param  [type]  $params   记录信息
    * @param  [type]  $sub_code 需要打包的子码数组
    * @param  integer $type     类型： 1 打包  2 拆分 
    * @return [type]            [description]
    */
    public function package($params,$sub_code,$type=1)
    {
        try {
            	$packageParam = [];
            	$recordParam = [];
              
              $select = ['codes.id as code_id','things.id as thing_id','things_codes.id as tc_id'];
            	$block_code_info = (new Code())->getCodeInfo($params['block_code'],$select)[0];
            	foreach ($sub_code as $v ) {

              		//打包关系
              		$package = [
              			'type' => $type,
              			'to_tc_id' => $type == GROUP_RECORDS ? $block_code_info['tc_id'] : $v['tc_id'],
              			'from_tc_id' => $type == GROUP_RECORDS ? $v['tc_id'] : $block_code_info['tc_id'],
              		];
              		array_push($packageParam, $package);

              		//打包记录
              		$record = [
              			'uploader_id' => $params['uploader_id'],
              			'uploader' => $params['uploader'],
              			'status' => $params['status'],
              			'note' => $params['note'],
              			'ext_info' => $params['ext'],
              			'tc_id' => $v['tc_id'],
              			'thing_id' => $v['thing_id'],
              			'code_id' => $v['code_id'],
                    'type' => $type,
                    'latitude' => $params['latitude'],
                    'longitude' => $params['longitude']

              		];
              		array_push($recordParam, $record);
            		
            	}

              Log::debug('package insert table package:'. var_export($packageParam, true));
              Log::debug('record insert table record:'. var_export($recordParam, true));
              
    			    DB::beginTransaction();

            	if(!Package::insert($packageParam)) throw new Exception(trans('message.system busy'));
              
            	if(!Record::insert($recordParam)) throw new Exception(trans('message.system busy'));

              //打包记录
              (new Record)->add($params,$block_code_info,$type);

            	Log::info('return package service result');
            	DB::commit();
            	
            	return ['block_code'=>$params['block_code']];

        } catch (Exception $e) {
        	
          	DB::rollBack();
          	$error = [
          		'msg' => $e->getMessage(),
          		'trace' => $e->getTrace()[0],
          	];
          	Log::error('package:'. var_export($error, true));
            
            returnResult(RES_SYSTEM_ERROR, trans('message.system busy'), []);

        } 
     
    }



    

}