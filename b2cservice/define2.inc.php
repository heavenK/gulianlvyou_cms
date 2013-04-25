<?php
	require(dirname(__FILE__).'/define.inc.php');
	//全局
    define('B2CSERVICE_URL',ET_URL.'b2cservice/');
    define('ORDER_URL',ET_URL.'b2cservice/IndexOrder/');
    define('MY_URL',ET_URL.'b2cservice/IndexMy/');
	//常用定义
    define('SITE_INDEX',ET_URL.'index.php?s=/');
    define('SITE_DATA',ET_URL.'Data/');
    define('SERVER_INDEX',SERVER_URL.'index.php?s=/');
	define('SITE_PUBLIC',ET_URL.'demand/');
	define('__PUBLIC__',B2CSERVICE_URL."Public");
	define('SITE_URL',ET_URL);
	//配置
	define('MODE_NAME','mycore');
	define('THINK_PATH',dirname(__FILE__).'/../../ThinkPHP/');
	define('APP_NAME', 'b2cservice');
	define('APP_PATH', dirname(__FILE__).'/B2CService/');
	define('DEFAULT_TYPE','default');
	define('APP_DEBUG', true);
	
	require(APP_PATH.'Common/Function.php');
	require(APP_PATH.'Common/B2CFunction.php');
	
?>