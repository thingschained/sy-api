<?php


define('RES_SUCCESS',0);//请求成功
define('RES_PARAM_REQUIRED',30001);//必选参数不能为空
define('RES_PARAM_ERROR',30002);//数据格式错误
define('RES_REQUEST_ERROR',30003);//用户请求过于频繁
define('RES_CODE_NO_EXISTS',30004);//赋码不存在
define('RES_THINGS_ERROR',30005);//商品不存在
define('RES_BLOCK_ERROR',30006);//区块对象不存在
define('RES_SPLIT_COUNT_ERROR',30007);//拆分数量错误
define('RES_GROUP_CODE_ERROR',30008);//赋码不是聚合码
define('RES_GROUP_SUB_CODE_ERROR',30009);//子赋码格式错误
define('RES_GROUP_SUB_NO_EXISTS',300010);//子赋码格式错误
define('RES_SYSTEM_ERROR',50001);//系统繁忙,请稍后再试
define('RES_SYSTEM_MAINTAIN',50002);//系统维护中
define('RES_CONTACT_SERVICE',50003);//系统错误,请联系客服

//赋码类型：0：物品赋码 1：物品位置流转/位置流转 3：聚合 4：拆分 5：孳息   8：转换
define('ASSIGN_CODE',0);
define('FLOW_LOCATION_CODE',1);
define('GROUP_CODE',3);
define('SPLIT_CODE',4);
define('BREED_CODE',5);
define('CONVERT_CODE',8);

//记录类型 类型： 1 打包  2 拆分 3 转化 4 孳息 5 位置流转
define('GROUP_RECORDS',1);
define('SPLIT_RECORDS',2);
define('CONVERT_RECORDS',3);
define('BREED_RECORDS',4);
define('FLOW_LOCATION_RECORDS',5);
