<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Models\{Code,Record};
use Illuminate\Support\Facades\Log;
use App\Service\CodeService;

error_reporting(E_ALL^E_NOTICE);

class CodeController extends Controller
{


	protected $rules = [
                        'block_code' => 'required',
                    ];

    public function __construct(Request $request)
    { 
        parent::__construct($request);
        $this->message =[
                    'block_code.required' => trans('message.block_code required') ,
                ];
    }
    /**
     * 赋码检查
     * @param  Request $request [description]
     * @return [type]           [description]
     */
	public function checkcode(Request $request)
    {
        $this->param['block_code'] = $request->input('block_code');
		
		//验证
        $this->validator($this->param, $this->rules, $this->message); 

        $res = (new Code)->getCodeInfo($this->param['block_code']);
        if(empty($res)) returnResult(RES_CODE_NO_EXISTS, trans('message.code not exists'));

        (new CodeService)->checkcode($this->param['block_code']);
 
        return returnResult(RES_SUCCESS, 'success', []);        
    }


    /**
     * 物品详细信息查询
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function codequery(Request $request)
    {
        $this->param['block_code'] = $request->input('block_code');

        $this->validator($this->param, $this->rules, $this->message); //验证

        $res = (new Code)->getCodeInfo($this->param['block_code']);
        if(empty($res)) returnResult(RES_CODE_NO_EXISTS, trans('message.code not exists'));

        $result = (new Code)->codequery($this->param['block_code']);

        return returnResult(RES_SUCCESS, 'success', $result);   
    }

    /**
     * 物品流转信息查询
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function tracequery(Request $request)
    {
        
    	$this->param['block_code'] = $request->input('block_code');

        $this->validator($this->param, $this->rules, $this->message); //验证

        $res = (new Code)->getCodeInfo($this->param['block_code']);
        if(empty($res)) returnResult(RES_CODE_NO_EXISTS, trans('message.code not exists'));

        $result = (new Record)->tracequery($this->param['block_code']);

        return returnResult(RES_SUCCESS, 'success', $result); 
    }

}