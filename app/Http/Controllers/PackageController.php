<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Models\{Thing,Package,Code};
use Illuminate\Support\Facades\Log;
use App\Service\CodeService;

error_reporting(E_ALL^E_NOTICE);

class PackageController extends Controller
{

	protected $rules = [

                        'package' => [
                            'sub_code' => 'required',
                            'sub_info' => 'required',
                            'uploader'=>'required',
                            'uploader_id'=>'required',
                            'longitude'=>'required',
                            'latitude'=>'required',
                        ],
                        
                        'add_group' => [
                            'sn'=>'required',
                            'tid'=>'required',
                            'title'=>'required',
                            'sku'=>'required',

                        ],
                    ];

    public function __construct(Request $request)
    { 

        parent::__construct($request);
        $this->message =[
                    'title.required' => trans('message.title required') ,
                    'sku.required' => trans('message.sku required') ,
                    'tid.required' => trans('message.tid required'),
                    'sn.required' => trans('message.sn required'),
                    'longitude.required '=> trans('message.longitude required'),
                    'latitude.required' => trans('message.longitude required'),
                    'block_code.required' => trans('message.block_code required') ,
                    'sub_code.required' => trans('message.sub_code required') ,
                    'sub_info.required' => trans('message.sub_info required') ,
                    'uploader.required' => trans('message.uploader required') ,
                    'uploader_id.required' => trans('message.uploader_id required') ,
                ];

    }

    /**
     * 物品打包
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function package(Request $request)
    {

        $block_code = $request->input('block_code');
        if($block_code)
        {
            $this->param['block_code'] = $block_code;
            $res = (new Code)->getCodeInfo($block_code,['codes.type']);

            //检查赋码是否存在
            if(empty($res)) returnResult(RES_CODE_NO_EXISTS, trans('message.code not exists'));
            
            //检查赋码类型
            if($res[0]['type'] != GROUP_CODE) returnResult(RES_GROUP_CODE_ERROR, trans('message.code error'));
            
        } 

        $this->param['sub_code'] = json_decode(stripslashes($request->input('sub_code')),ture);
        $this->param['longitude'] = $request->input('longitude');
        $this->param['latitude'] = $request->input('latitude');
        $this->param['uploader'] = $request->input('uploader');
        $this->param['uploader_id'] = $request->input('uploader_id');
        $this->param['status'] = $request->input('status');
        $this->param['note'] = $request->input('note');
        $this->param['ext'] = $request->input('ext') ??  ""  ;

        //初次打包需要的参数
        $addParam['title'] = $request->input('title');
        $addParam['sku'] = $request->input('sku');
        $addParam['sn'] = $request->input('sn');
        $addParam['tid'] = $request->input('tid');
        $addParam['longitude'] = $this->param['longitude'];
        $addParam['latitude'] = $this->param['latitude'];
        $addParam['ext'] = $this->param['ext'];

        $this->validator($this->param, $this->rules['package'], $this->message); //验证 

        //检查子赋码是否都存在
        $select = ['codes.id as code_id','things.id as thing_id','things_codes.id as tc_id'];
        
        $package = new Package();
        $code = new Code();

        //子码详情
        $sub_code_detail = $code->getCodeInfo($this->param['sub_code'],$select);
        
        if(!is_array($this->param['sub_code']) && count($this->param['sub_code']) > 1 ) 
        {
             returnResult(RES_GROUP_SUB_CODE_ERROR,trans('message.sub code error'));
        }

        if(count($sub_code_detail) != count(array_unique($this->param['sub_code'])) ||  !count($sub_code_detail) )
        {
             returnResult(RES_GROUP_SUB_NO_EXISTS,trans('message.sub code not exists'));
        }


        $this->param['sub_code'] = array_unique($this->param['sub_code']);

        //打包
        if($block_code)
        {
            //追加聚合
            $block_code =  (new CodeService())->addGroup( $this->param['block_code'],$this->param['sub_code']);

        }else{
            
            //验证
            $this->validator($addParam, $this->rules['add_group'], $this->message);  
            //聚合
            $block_code = (new CodeService())->group($this->param['sub_code'],$addParam['title'],$this->param['latitude'],$this->param['longitude'],$addParam['sku']);

            //添加物品信息
            (new Thing)->add($addParam,$block_code,GROUP_CODE);
            $sub_code_detail = $code->getCodeInfo($this->param['sub_code'],$select);
        }

        $this->param['block_code'] = $block_code;
        $result = $package->package($this->param,$sub_code_detail);

        returnResult(RES_SUCCESS, 'success', $result);        
    }

    /**
     * 物品拆包
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function unpackage(Request $request)
    {
        
        $sub_info = $request->input('sub_info');
        $this->param['block_code'] = $request->input('block_code');
        $this->param['sub_info'] = $sub_info;
        $this->param['longitude'] = $request->input('longitude');
        $this->param['latitude'] = $request->input('latitude');
        $this->param['uploader'] = $request->input('uploader');
        $this->param['uploader_id'] = $request->input('uploader_id');
        $this->param['status'] = $request->input('status');
        $this->param['note'] = $request->input('note');
        $this->param['ext'] = $request->input('ext') ??  ""  ;

        //检查赋码是否存在
        $res = (new Code)->getCodeInfo($this->param['block_code']);
        if(empty($res)) returnResult(RES_CODE_NO_EXISTS, trans('message.code not exists'));

        $this->validator($this->param, $this->rules['package'], $this->message);
        $this->param['sub_info'] = json_decode(stripslashes($sub_info),ture);
        $this->param['count'] = count($this->param['sub_info']);        
  
        $package = new Package();
        $thing = new Thing();
        $code = new Code();

        $sub_code =  (new CodeService())->split($this->param['block_code'],$this->param['sub_info'],$this->param['count']);

        //添加拆分码信息
        foreach ($sub_code as $i => $vo) 
        {
            $addParam = [];
            $info =  $this->param['sub_info'][$i];
            $addParam['title'] = $info['title'];
            $addParam['sku'] = $info['sku'];
            $addParam['sn'] = $info['sn'];
            $addParam['tid'] = $info['tid'];
            $addParam['longitude'] = $this->param['longitude'];
            $addParam['latitude'] = $this->param['latitude'];
            $addParam['ext'] = $this->param['ext'];

            $thing->add($addParam,$vo,4);
        }
        
        $select = ['codes.id as code_id','things.id as thing_id','things_codes.id as tc_id'];
        $sub_code_detail = $code->getCodeInfo($sub_code,$select);

        unset($this->param['sub_info']);
        $result = $package->package($this->param,$sub_code_detail,SPLIT_RECORDS);
        $result['sub_code']  = $sub_code;

        returnResult(RES_SUCCESS, 'success', $result);        

    }

}

