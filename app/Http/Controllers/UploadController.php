<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Models\{Record,Code};
use Illuminate\Support\Facades\Log;
use App\Service\CodeService;

error_reporting(E_ALL^E_NOTICE);

class UploadController extends Controller
{

	 protected $rules = [
	 					'block_code' => 'required',
                        'uploader'=>'required',
                        'uploader_id'=>'required',
                        'longitude'=>'required',
                        'latitude'=>'required',
                    ];

    public function __construct(Request $request)
    { 
        parent::__construct($request);
        $this->message =[
                    'longitude.required '=> trans('message.longitude required'),
                    'latitude.required' => trans('message.longitude required'),
                    'block_code.required' => trans('message.block_code required') ,
                    'uploader.required' => trans('message.uploader required') ,
                    'uploader_id.required' => trans('message.uploader_id required') ,
                ];

    }

 	/**
     * 处理SDK上报的物品经纬度信息
     * 
     * @param  Request $request
     */
    public function upload(Request $request)
    {
        try {


            $this->param['block_code'] = $request->input('block_code');
            $this->param['latitude'] = $request->input('latitude');
            $this->param['longitude'] = $request->input('longitude');
            $this->param['uploader'] = $request->input('uploader');
            $this->param['uploader_id'] = $request->input('uploader_id');
            $this->param['status'] = $request->input('status');
            $this->param['note'] = $request->input('note');
            $this->param['ext'] = $request->input('ext') ??  ""  ;

            $this->validator($this->param, $this->rules, $this->message); //验证
            
            DB::beginTransaction();

            $select = ['codes.id as code_id','things.id as thing_id','things_codes.id as tc_id'];
            $thing_info = (new Code())->getCodeInfo($this->param['block_code'],$select);
            if(empty($thing_info)) returnResult(RES_CODE_NO_EXISTS, trans('message.code not exists'));

            $thing_info = $thing_info[0];

            //添加记录
            (new Record)->add($this->param,$thing_info,FLOW_LOCATION_RECORDS);
            
            DB::commit();

            returnResult(RES_SUCCESS,'success');

        } catch (Exception $e) {
            
            DB::rollBack();
            $error = [
                'msg' => $e->getMessage(),
                'trace' => $e->getTrace()[0],
            ];
            Log::error('uploader:'. var_export($error, true));
            
            returnResult(RES_SYSTEM_ERROR);
        }

    }

}