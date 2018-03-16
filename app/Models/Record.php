<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 记录模型
 *
 * @author jerry<cuiliang.hu@thingschained.com>
 */
class Record extends Model
{

    /**
     * 添加物品记录
     * @param [type] $param      数据
     * @param [type] $code_id    流转记录赋码id
     * @param [type] $thing_info 流转物品信息
     * @param [type] $type 类型： 1 打包  2 拆分 3 转化 4 孳息 5 位置流转
     */
	public function add($params,$thing_info,$type)
	{
		Log::info('call upload service');
        
        Log::debug('Record:'. var_export($params, true));

        //打包记录
        $record = [
            'uploader_id' => $params['uploader_id'],
            'uploader' => $params['uploader'],
            'status' => $params['status'],
            'note' => $params['note'],
            'ext_info' => $params['ext'],
            'tc_id' => $thing_info['tc_id'],
            'thing_id' => $thing_info['thing_id'],
            'code_id' => $thing_info['code_id'],
            'type' => $type,
            'longitude' => $params['longitude'],
            'latitude' => $params['latitude'],
        ];
        
        if(!Record::insert($record)) throw new Exception(trans('message.system busy'));
    
        Log::debug('Record insert table Record:'. var_export($record, true));
   
        Log::info('return upload service result');
	}


    /**
     * 查询流转记录
     * @param  [type] $block_code 查询赋码
     * @return [type]             [description]
     */
    public function tracequery($block_code)
    {
        Log::info('call tracequery service');
        
        $where = [
            'codes.block_code' => $block_code,
        ];

        $select = [
            'codes.block_code', 'records.uploader', 'records.uploader_id','records.type',
            'records.status', 'records.note','records.ext_info','records.latitude','records.longitude',
            'records.created_at'
        ];
        $list = DB::table('codes')
                ->leftJoin('things_codes','things_codes.code_id','=','codes.id')
                ->leftJoin('records','records.tc_id','=','things_codes.id')
                ->where($where)
                ->select($select)
                ->get();

        Log::info('return tracequery service result');

        return $list;
    }
}