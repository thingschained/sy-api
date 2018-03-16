<?php

namespace ThinksChained\Log;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use ThinksChained\Log\ThinksChainedLogConfig;

class ThinksChainedLog extends Logger
{
	public static $log = null;
	
	protected $log_path = './';
	protected $log_name = null;
	protected $log_level = Logger::DEBUG;
	protected $log_maxFiles = 0;
	
	private $formatter = null;
	private $handler = null;
	private $filepath = '';
	
	public static function initLog($logConfig= [])
	{
		$thinkschainedlog = new ThinksChainedLog(''); 
		$thinkschainedlog->setConfig($logConfig);
		$thinkschainedlog->cheakDir();
		$thinkschainedlog->setFilePath();
		$thinkschainedlog->setFormat();
		$thinkschainedlog->setHande();
	}
	private function setFormat()
	{
		$output = '[%datetime%][%level_name%]'.'%message%' . "\n";
		$this->formatter = new LineFormatter($output);
		
	}
	private function setHande()
	{
		self::$log = new Logger('trace_logger');
		$rotating = new RotatingFileHandler($this->filepath, $this->log_maxFiles, $this->log_level);
		$rotating->setFormatter($this->formatter);
		self::$log->pushHandler($rotating);
	}
	private function setConfig($logConfig= [])
	{
		$arr = empty($logConfig) ? TraceConfig::LOG_FILE_CONFIG : $logConfig;
		$this->log_path = iconv('UTF-8', 'GBK',$arr['FilePath']);
		$this->log_name = iconv('UTF-8', 'GBK',$arr['FileName']);
		$this->log_maxFiles = is_numeric($arr['MaxFiles']) ? 0 : intval($arr['MaxFiles']);
		$this->log_level = $arr['Level'];
	}
	private function cheakDir()
	{
		if (!is_dir($this->log_path)){
			mkdir($this->log_path, 0755, true);
		}
	} 
	private function setFilePath()
	{
		$this->filepath =  $this->log_path.'/'.$this->log_name;
	}
	private static function writeLog($level, $msg)
	{
		switch ($level) {
			case DEBUG:
				self::$log->debug($msg);
				break;
			case INFO:
				self::$log->info($msg);
				break;
			case NOTICE:
				self::$log->notice($msg);
				break;
			case WARNING:
				self::$log->warning($msg);
				break;
			case ERROR:
				self::$log->error($msg);
				break;
			case CRITICAL:
				self::$log->critical($msg);
				break;
			case ALERT:
				self::$log->alert($msg);
				break;
			case EMERGENCY:
				self::$log->emergency($msg);
				break;
			default:
				break;
		}
		
	}
	
	public static function commonLog($level, $format, $args1 = null, $arg2 = null)
	{
		ThinksChainedLog::initLog();
		$msg = sprintf(urldecode($format),$args1,$arg2);
		$back = debug_backtrace();
		$line = $back[0]['line'];
		$funcname = $back[1]['function'];
		$filename = basename($back[0]['file']);
		$message = '['.$filename.':'.$line.']: '.$msg;
		ThinksChainedLog::writeLog($level, $message);
		
	}
}
