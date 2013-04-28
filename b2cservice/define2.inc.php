<?php
	require(dirname(__FILE__).'/define.inc.php');
	//全局
    define('B2CSERVICE_URL',SITE_URL.'b2cservice/');
    define('ORDER_URL',SITE_URL.'b2cservice/IndexOrder/');
    define('MY_URL',SITE_URL.'b2cservice/IndexMy/');
	//常用定义
    define('SITE_INDEX',SITE_URL.'index.php?s=/');
    define('SITE_DATA',SITE_URL.'Data/');
    define('SERVER_INDEX',SERVER_URL.'index.php?s=/');
	define('SITE_PUBLIC',SITE_URL.'demand/');
	define('__PUBLIC__',B2CSERVICE_URL."Public");
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