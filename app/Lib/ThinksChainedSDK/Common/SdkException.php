<?php

namespace ThinksChained\Common;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use ThinksChained\Log\ThinksChainedLog;
  

class SdkException extends \RuntimeException
{ 
    /**
     * @var string Exception code
     */
    protected $exceptionCode;
    
    /**
     * @var string Exception message
     */
    protected $exceptionMessage;

	
    public function __construct ($message = null, $code = null, $previous = null) 
    { 
        parent::__construct($message, $code, $previous);

        $this->setExceptionCode($code);
        $this->setExceptionMessage($message);
        
        self::save($message,$code);
    }

    public function setExceptionCode($code)
    {
        $this->exceptionCode = $code;
    }

    public function getExceptionCode()
    {
        return $this->setExceptionCode;
    }

    public function setExceptionMessage($message)
    {
        $this->exceptionMessage = $message;
    }

    public function getExceptionMessage()
    {
        return $this->exceptionMessage;
    }

    public function save($message,$code)
    {    
        ThinksChainedLog::commonLog(INFO, 'code:'.$code.', message:'.$message);
        return true;
    }
    
}
