<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 商品赋码关系表
 *
 * @author jerry<cuiliang.hu@thingschained.com>
 */
class ThingsCode extends Model
{

	/**
	 * 商品和赋码添加关系
	 * @param [type] $thing_id 		物品id
	 * @param [type] $sn       		物品条码
	 * @param [type] $tid      		物品在商户系统的唯一标识
	 * @param [type] $code_id       赋码id
	 */
	public static function addRelations($thing_id,$sn,$tid,$code_id)
	{

		$data = [
			'thing_id' => $thing_id,
			'sn' => $sn,
			'tid' => $tid,
			'code_id' => $code_id 
		];

		return self::insertGetId($data);
	}


}