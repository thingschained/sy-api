<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 商品模型
 *
 * @author jerry<cuiliang.hu@thingschained.com>
 */
class Thing extends Model
{

	/**
     * 将物品信息进行赋码，并保存赋码对象ID
     * 
     * @param  array $params 物品信息
     * @param  string $block_code 赋码
     * @param integer $type      0：物品赋码 1：物品位置流转/位置流转 2：物品权利流转 3：聚合 4：拆分 5：孳息 6：单物关联 7：解关联  8：转换
     * @return array 物品赋码后的信息
     */
	public function add($params,$block_code,$type=0)
	{
		Log::info('call assigncode service');

        Log::debug('assigncode:'. var_export($params, true));

        try {

            //数据处理
        	$tid = $params['tid'];
            $params['specifications'] = $params['sku'];
            $params['ext_info'] = $params['ext'];
            unset($params['tid'],$params['sku'],$params['ext']);
            $params['data_hash'] = hash('md5', implode(',', $params));

			DB::beginTransaction();
        	//添加数据
        	$thing_id = self::insertGetId($params);
        	if($thing_id)
        	{
        		//添加赋码
				$code_id = Code::add($block_code,$type);
        		//数据关联
                ThingsCode::addRelations($thing_id,$params['sn'],$tid,$code_id);
                
        		Log::info('return assigncode service result');
        		DB::commit();
        		return [
        			'tid' => $tid,
        			'block_code' => $block_code,
        		];
        	}
            
        	DB::rollBack();
        	Log::error('assigncode: add fail'. var_export($params, true));

            returnResult(RES_SYSTEM_ERROR);

        } catch (Exception $e) {
        	DB::rollBack();
        	$error = [
        		'msg' => $e->getMessage(),
        		'trace' => $e->getTrace()[0],
        	];
        	Log::error('assigncode:'. var_export($error, true));
            
            returnResult(RES_SYSTEM_ERROR);

        }
        
	}
}