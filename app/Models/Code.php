<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Service\CodeService;

/**
 * 赋码模型
 *
 * @author jerry<cuiliang.hu@thingschained.com>
 */
class Code extends Model
{
	/**
	 * 添加赋码
	 * @param [type]  $block_code 赋码ID
	 * @param integer $type      0：物品赋码 1：物品位置流转/位置流转 2：物品权利流转 3：聚合 4：拆分 5：孳息 6：单物关联 7：解关联  8：转换
	 */
	public static function add($block_code,$type=0)
	{
		$data = [
			'block_code' => $block_code,
			'type' => $type,
		];

		return self::insertGetId($data);
	}


	/**
	 * 获取赋码详情
	 * @param  [type] $block_code 赋码
	 * @param  array  $select     搜索字段
	 * @return [type]             [description]
	 */
	public function getCodeInfo($block_code,$select=['*'])
	{

		$where = is_array($block_code) ?  $block_code : [$block_code];

		$data = Code::select($select)
			->leftJoin('things_codes','things_codes.code_id','=','codes.id')
			->leftJoin('things','things.id','=','things_codes.thing_id')
			->whereIn('codes.block_code',$where)
			->get();

		$data = $data ? $data->toArray() : [];

		return $data;
	}


	 /**
     * 物品赋码查询
     * 
     * @param  array $params 物品赋码
     * @return array 物品信息
     */
    public function codequery($block_code)
    {
        Log::info('call codequery service');

        Log::debug('codequery:'. $block_code);

        $code = new Code();
        $select = [
        			'things_codes.sn','codes.block_code','things_codes.tid','things.title',
        			'things.alias','things.specifications','codes.block_hash','codes.wtx_hash','things.latitude',
        			'things.longitude','things.ext_info','codes.type'
    			];

        $code_info = $this->getCodeInfo($block_code,$select)[0];
        
        if(!$code_info['block_hash'] || !$code_info['wtx_hash'])
        {
        	//获取区块信息
	        $result = (new CodeService)->objectInfo($block_code);
	        if(is_array($result) && !empty($result))
	        {
	        	Log::debug('codequery:'. var_export($result, true));

	        	$code_info['block_hash'] = $result['wtx_hash'];
	        	$code_info['wtx_hash'] = $result['block_hash'];

	        	Code::where('block_code',$block_code)->update(['block_hash' => $result['wtx_hash'] , 'wtx_hash' => $result['block_hash']]);
	        }
	        
        }


        return $code_info;
    }

    

}	