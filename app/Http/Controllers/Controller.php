<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{

	protected $rules;
    protected $message;
    protected $param = [];  

    public function __construct(Request $request)
    { 

        Log::info('prepare request',['url'=>$request->fullUrl(),'method'=>$request->method(),'request'=>$request->all()]);
    }


	protected function validator($param, $rule, $msg)
	{
		foreach($rule as $k=>$v)
		{
			if(!array_key_exists($k, $param)) unset($rule[$k]);
		}

		$validator = Validator::make($param, $rule, $msg);
		if($validator->fails())
		{
			Log::info('validate request faild');
		    
		    returnResult(RES_PARAM_REQUIRED, trans('message.required params'),$validator->errors()->all()[0]);
		}
		else
		{
			Log::info('validate request success');
		}
	}
}
