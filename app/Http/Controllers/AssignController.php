<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\{DB,Log};
use App\Models\{Thing};
use App\Service\CodeService;

error_reporting(E_ALL^E_NOTICE);

class AssignController extends Controller
{

    protected $rules = [
                        'sn'=>'required',
                        'tid'=>'required',
                        'title'=>'required',
                        'sku'=>'required',
                        'longitude'=>'required',
                        'latitude'=>'required',
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
                ];

    }

    /**
     * 获取物品聚合码
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function assigngroup(Request $request)
    {

        $this->param['sn'] = $request->input('sn');
        $this->param['tid'] = $request->input('tid');
        $this->param['title'] = $request->input('title');
        $this->param['alias'] = $request->input('alias');
        $this->param['unspsc'] = $request->input('unspsc');
        $this->param['brand_name'] = $request->input('brand_name');
        $this->param['sku'] = $request->input('sku');
        $this->param['latitude'] = floatval($request->input('latitude')) ;
        $this->param['longitude'] = floatval($request->input('longitude'));
        $this->param['ext'] = $request->input('ext');

        $this->validator($this->param, $this->rules, $this->message); //验证 

        //赋码
        $block_code = (new CodeService())->assigncode($this->param);
        
        $result = (new Thing)->add($this->param,$block_code,ASSIGN_CODE);

        returnResult(RES_SUCCESS,'success',$result);

       
    }

}