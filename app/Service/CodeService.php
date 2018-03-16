<?php

namespace App\Service;

use Illuminate\Support\Facades\Log;
use App\Lib\ThinksChainedSDK\ThinksChainedClient;
/**
 * 与赋码服务对接类
 *
 * @author jerry<cuiliang.hu@thingschained.com>
 */
class CodeService 
{

	protected $sdk;
	protected $params;

	public function __construct()
    { 

    	$this->sdk = new ThinksChainedClient();
    	$this->params = [
                'tradeNo'=>quickrandom(),
                'srcHash'=>quickrandom(16)
    	];
    }

	public function assigncode($param)
	{
		Log::info('call assigncode service');
        
		$param['body'] = $param['title'];
		$params = array_merge($this->params,$param);

		Log::debug('assigncode:'. var_export($params, true));

		$res = $this->sdk->assigncode($params);
		$res = json_decode($res,true);

		Log::debug('assigncode:'. var_export($res, true));
		if ($res['code'] == 1)
		{
            returnResult(RES_SYSTEM_ERROR,trans('message.system error'));
        }
		
		Log::info('return assigncode service result');

		return $res['data']['objectId'];

	}

	public function checkcode($block_code)
	{
		Log::info('call checkcode service');

		$param['objectId'] = $block_code;
        $params = array_merge($this->params,$param);

        Log::debug('checkcode:'. var_export($params, true));

        $res = $this->sdk->checkObject($params);
        $res = json_decode($res,true);

		Log::debug('checkcode:'. var_export($res, true));

		if ($res['code'] == 1)
		{
            returnResult(RES_SYSTEM_ERROR,trans('message.system error'));
        }

        Log::info('return checkcode service result');

        return $res;
	}

	public function objectInfo($block_code)
	{

		Log::info('call objectInfo service');

		$param['objectId'] = $block_code;
        $params = array_merge($this->params,$param);

        Log::debug('objectInfo:'. var_export($params, true));

        $res = $this->sdk->objectInfo($params);
        $res = json_decode($res,true);
		
		Log::debug('objectInfo:'. var_export($res, true));

        Log::info('return objectInfo service result');

        return $res['data'];
	}


	/**
	 * 追加聚合上链
	 * @param [type] $block_code 打包赋码
	 * @param [type] $sub_code   被打包的子赋码
	 */
	public function addGroup($block_code,$sub_code)
	{
		Log::info('call addGroup service');

		$addgroupParam['itemObjects'] = [];
        foreach($sub_code as $k=>$v)
        {
            $addgroupParam['itemObjects'][] = $v;
        }

        $addgroupParam['objectId'] = $block_code;
        $addgroupParam['itemObjects'] = implode(',', $addgroupParam['itemObjects']);
        $params = array_merge($this->params,$addgroupParam);

        Log::debug('addGroup:'. var_export($params, true));

        $result = $this->sdk->addGroup($params);
		$res = json_decode($result,true);

        Log::debug('addGroup:'. var_export($res, true));

		if ($res['code'] == 1)
		{
            returnResult(RES_SYSTEM_ERROR,trans('message.system error'));
        }

        Log::info('return addGroup service result');

        return  $res['data']['objectId']; 
	}

	public function group($sub_code,$body,$lat,$long,$sku)
	{
		Log::info('call group service');

		//获取聚合码
		$addgroupParam = [
		      'itemObjects' => implode(',', $sub_code),
		      'body' => $body,
		      'latitude' => $lat,
		      'longitude' => $long,
		      'sku' => $sku,
		];
		$params = array_merge($this->params,$addgroupParam);

        Log::info('group:'. var_export($params, true));

		$res = $this->sdk->group($params);
	    $res = json_decode($res,true);

        Log::info('group:'. var_export($res, true));

		if ($res['code'] == 1)
		{
            returnResult(RES_SYSTEM_ERROR,trans('message.system error'));
        }

        Log::info('return addGroup service result');

        return  $res['data']['objectId']; 

	}


	public function split($block_code,$sub_info,$count)
	{
	   	Log::info('call split service');

		$splitAsyncParam['itemObjects'] = [];
		$unpackageParam = [];
		foreach ($sub_info as $k=>$v) 
		{        
		    // 拼接格式： 信息,规格,经度,纬度
		    $splitAsyncParam['itemObjects'][] = "{$v['title']},{$v['sku']},{$v[longitude]},{$v['latitude']}";
		}
		$splitAsyncParam['objectId'] = $block_code;
		$splitAsyncParam['count'] = $count;
		$splitAsyncParam['itemObjects'] = implode('|', $splitAsyncParam['itemObjects']);
		$params = array_merge($this->params,$splitAsyncParam);

		Log::info('split:'. var_export($params, true));

		$res = $this->sdk->split($params);
		$res = json_decode($res,true);

        Log::info('split:'. var_export($res, true));

		if ($res['code'] == 1)
		{
	        returnResult(RES_SYSTEM_ERROR,trans('message.system error'));
	    }

        Log::info('return split service result');

	    return  $res['data']['itemObjects']; 
	}






}