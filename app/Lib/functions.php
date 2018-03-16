<?php
if( !function_exists('decryptAES') )
{
	function decryptAES( $content )
	{
		$aes = new AES();
		$sign = substr($content,-32);
		$content = substr($content,0,-32);
		if ($sign !== md5($content))
		{
			$params = '';
		}
		else
		{
			$content = $aes->decrypt(env('API_ENCRYPTION_KEY'),$content);
			$content = substr ( $content, 0, strrpos ( $content, '}' )+1);
			$params = json_decode($content,true);
		}	
		return $params;	
	}
}


function quickrandom($len=32)
{
	$pool="0123456789abcdefghihklmnopqrstuvwxyzABCDEFGHIHKLMNOPQRSTUVWXYZ";
	return substr(str_shuffle(str_repeat($pool,$len)), 0, $len);
	
}
function transApiParam($param)
{
    ksort($param);
    $signStr='';
	foreach ($param as $key => $value) {
		$signStr  = $signStr . $key.'='.$value;
	}
	$param['sign'] = md5($signStr.env('SECRET'));
	return $param;
}
function returnResult($ret, $msg='', $data = '')
{
	Log::info('return api result', ['ret'=>$ret,'msg'=>$msg,'data'=>$data]);

    echo json_encode(['ret'=>$ret,'msg'=>$msg,'data'=>$data]);
	die;

}

/**
 * get request id 
 *
 * @return string
 */
function getRequestId()
{
	return sha1(mt_rand(12345678, mt_getrandmax()).$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_TIME_FLOAT'].$_SERVER['REMOTE_ADDR']);
}

?>