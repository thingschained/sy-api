<?php
namespace App\Lib\ThinksChainedSDK;

require_once(__DIR__.'/vendor/autoload.php');
require_once(__DIR__.'/autoloader.php');

use ThinksChained\Common\SdkConfig;
use ThinksChained\Common\SdkException;

class ThinksChainedClient
{
    protected $api_env;
    public function __construct()
    {
        $this->api_env['base_uri'] = SdkConfig::BASE_URI;
        $this->api_env['key'] = SdkConfig::KEY;
        $this->api_env['secret'] = SdkConfig::SECRET;
        $this->api_env['third_uri'] = SdkConfig::THIRD_URI;
        if(!$this->api_env) {
            echo 'faild';
            return false;
        }
    }
    /**
     * 赋码
     */
    public function assigncode($params)
    {
        $apiparam = $this->transApiParam(
                        [
                            'tradeNo'=>$params['tradeNo'],
                            'key'=>$this->api_env['key'],
                            'body'=>$params['body'],
                            'latitude'=>$params['latitude'],
                            'longitude'=>$params['longitude'],
                            'sku'=>$params['sku'],
                            'url'=>$this->api_env['third_uri'],
                            'srcHash'=>$params['srcHash']
                        ]
                    );

        $result = $this->curlPost($apiparam);

        return $result;
    }

    /**
     * 检查赋码
     */
    public function checkObject($params)
    {
    
        $apiparam = $this->transApiParam(
                [
                    'tradeNo'=>$params['tradeNo'],
                    'key'=>$this->api_env['key'],
                    'objectId'=>$params['objectId'],
                ]
            );
    
        $result = $this->curlPost($apiparam);

        return $result;
    }

    /**
     * 赋码对象详情
     */
    public function objectInfo($params)
    {

        $apiparam = $this->transApiParam(
                [
                    'tradeNo'=>$params['tradeNo'],
                    'key'=>$this->api_env['key'],
                    'objectId'=>strval($params['objectId'])
                ]
            );
            
        $result = $this->curlPost($apiparam);
        return $result;

       
    }

    /**
     * 聚合
     */
    public function addGroup($params)
    {

        $apiparam = $this->transApiParam(
                [
                    'tradeNo'=>$params['tradeNo'],
                    'key'=>$this->api_env['key'],
                    'objectId'=>$params['objectId'],
                    'itemObjects'=>$params['itemObjects'],
                ]
            );
    
        $result = $this->curlPost($apiparam);

        return $result;
        
    }

    /**
     * 拆分
     */
    public function split($params)
    {
    
        $apiparam = $this->transApiParam(
                [
                    'tradeNo'=>$params['tradeNo'],
                    'key'=>$this->api_env['key'],
                    'objectId'=>$params['objectId'],
                    'itemObjects'=>$params['itemObjects'],
                    'count'=>$params['count'],
                    'srcHash'=>$params['srcHash'],
                    'url'=>$this->api_env['third_uri'],
                ]
            );
        
        $result = $this->curlPost($apiparam);

        return $result;
        
    }
    
    /**
     * 孳息
     */
    public function breed($params)
    {
    
        $apiparam = $this->transApiParam(
                [
                    'tradeNo'=>$params['tradeNo'],
                    'key'=>$this->api_env['key'],
                    'objectId'=>$params['objectId'],
                    'itemObjects'=>$params['itemObjects'],
                    'count'=>$params['count'],
                    'srcHash'=>$params['srcHash'],
                    'url'=>$this->api_env['third_uri'],
                ]
            );

        $result = $this->curlPost($apiparam);

        return $result;    

    }
    
    /**
     * 查询孳息
     */
    public function breedChildren($params)
    {

        $apiparam = $this->transApiParam(
                [
                    'tradeNo'=>$params['tradeNo'],
                    'key'=>$this->api_env['key'],
                    'objectId'=>$params['objectId']
                ]
            );

        $result = $this->curlPost($apiparam);
    
        return $result;
        
    }

    /**
     * 转化
     */
    public function convert($params)
    {
       
        $apiparam = $this->transApiParam(
                [
                    'tradeNo'=>$params['tradeNo'],
                    'srcHash'=>$params['srcHash'],
                    'url'=>$this->api_env['third_uri'],
                    'key'=>$this->api_env['key'],
                    'objectId'=>$params['objectId'],
                    'itemObjects'=>$params['itemObjects'],
                    'count'=>$params['count'],
                ]
            );
        
        $result = $this->curlPost($apiparam);
    
        return $result;
        
    }
    /**
     * 聚合
     */
    public function group($params)
    {
        
        $apiparam = $this->transApiParam(
                [
                    'tradeNo'=>$params['tradeNo'],
                    'key'=>$this->api_env['key'],
                    'srcHash'=>$params['srcHash'],
                    'url'=>$this->api_env['third_uri'],
                    'itemObjects'=>$params['itemObjects'],
                    'body' => $params['body'],
                    'latitude' => $params['latitude'],
                    'longitude' => $params['longitude'],
                    'sku' => $params['sku'],
                ]
            );
        $result = $this->curlPost($apiparam);
    
        return $result;
    }
    /**
     * 聚合
     */
    public function flow($params)
    {
        
        $apiparam = $this->transApiParam(
                [
                    'tradeNo'=>$params['tradeNo'],
                    'key'=>$this->api_env['key'],
                    'srcHash'=>$params['srcHash'],
                    'objectId'=>$params['objectId'],
                    'latitude' => $params['latitude'],
                    'longitude' => $params['longitude'],
                    'right' => $params['right'],
                ]
            );
        $result = $this->curlPost($apiparam);
    
        return $result;
	}
	/**
	 * 通过CURL发送HTTP请求
	 */
    private function curlPost($postFields)
    {
        try {
            // $postFields = json_encode($postFields);
            $ch = curl_init ();
            curl_setopt( $ch, CURLOPT_URL, $this->api_env['base_uri'].'/code/'.debug_backtrace()[1]['function']); 
            curl_setopt( $ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $ch, CURLOPT_POST, 1 );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields);
            curl_setopt( $ch, CURLOPT_TIMEOUT,10); 
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
        
            $ret = curl_exec($ch);
            if (false == $ret) 
            {
               throw new SdkException(curl_error($ch),60001);
            } 
            else 
            {
                $rsp = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if (200 != $rsp) {
                    throw new SdkException(curl_error($ch), 60002);
                } else {
                    $result = $ret;
                }
            }
        } catch (SdkException $e) {
              $result['code'] = $e->getExceptionCode();
             $result['msg'] = $e->getExceptionMessage();
             $result = json_encode($result);

        }
        finally
        {
            curl_close ( $ch );
        }
		return $result;
    }

    private function transApiParam($param)
    {
        ksort($param);
        $signStr='';
        foreach ($param as $key => $value) {
            $signStr  = $signStr . $key.'='.$value;
        }

        $param['sign'] = md5($signStr.$this->api_env['secret']);

        return $param;
    }

}
