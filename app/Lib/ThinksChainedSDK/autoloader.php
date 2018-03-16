<?php


$mapping = [
	
	'ThinksChained\Common\SdkException' => __DIR__.'/Common/SdkException.php',
	'ThinksChained\Common\SdkConfig' => __DIR__.'/Common/SdkConfig.php',
	'ThinksChained\Log\ThinksChainedConfig' => __DIR__.'/Log/ThinksChainedConfig.php',
	'ThinksChained\Log\ThinksChainedLog' => __DIR__.'/Log/ThinksChainedLog.php',
];

spl_autoload_register(function ($class) use ($mapping) {
    if (isset($mapping[$class])) {
        require $mapping[$class];
    }
}, true);
