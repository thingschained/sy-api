# 星辰亿链溯源服务
## 项目介绍
一种数据结构化、对象化存储与查询服务的Restful API。 
星辰亿链为用户提供了一个简单的而又强大的API，旨在帮助用户快速高效的将星辰亿链的溯源服务整合到自己应用当中。 通过API可以快速实现如下功能：

• 物品原生赋码

• 物品的位置、温度数据上传

• 赋码物品打包

• 赋码物品分包

• 物品流转（溯源）信息查询

• 物品信息查询

## 项目特点
	
1.高度抽象：将数据对象化以适应各种业态与场景，提供一种低侵入、无业务状态的技术实现，与业务方逻辑松耦合。

2.跨平台：提供跨平台、高可用、高稳定性、高效率的HTTP(S)+JSON接口，不受现有IT系统或应用的开发语言限制。

3.快速接入：对星辰亿链区块链的标准接口进行封装，降低区块链对接的技术难度

4.无缝对接：提供多种应用接入方式，统一的错误码处理，助力应用快速接入

## 安装需求
	$ git clone git@github.com:liyu001989/lumen-api-demo.git
	$ composer install
	$ 设置 `storage` 目录必须让服务器有写入权限。
	$ cp .env.example .env
	$ vim .env
   		 DB_*
       填写数据库相关配置 your database configuration
    $ php artisan migrate

## REST API
API文档

* [码云](https://gitee.com/thingschained/sy-api.wiki.git) 
* [github](https://github.com/thingschained/sy-api/wiki/api)




