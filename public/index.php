<?php

try {
    $app = require __DIR__.'/../bootstrap/app.php';
    $app->run();
} catch (\Exception $e) {
    $log = new \Monolog\Logger('api-Exception');
    $log->pushHandler(new Monolog\Handler\StreamHandler(__DIR__.'/../storage/logs/'.date('Ymd').'_error_app.log'));
    $log->debug('msg: '.$e->getMessage().'  file: '.$e->getFile().'  line:  '.$e->getLine());

    returnResult(RES_CONTACT_SERVICE, trans('message.system error'), []);
}
