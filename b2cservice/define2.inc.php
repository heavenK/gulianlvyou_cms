<?php
	require(dirname(__FILE__).'/define.inc.php');
	//全局
    define('ROOT_URL','http://www.'.SITE_ROOT_NAME.'.com/');
    define('B2CSERVICE_URL',ROOT_URL.'b2cservice/');
    define('ORDER_URL','http://order.'.SITE_ROOT_NAME.'.com/');
    define('MY_URL','http://my.'.SITE_ROOT_NAME.'.com/');
    define('BBS_URL','http://faq.'.SITE_ROOT_NAME.'.com/');
	//常用定义
    define('SERVER_INDEX',SERVER_URL.'index.php?s=/');
    define('CLIENT_INDEX',CLIENT_URL.'index.php?s=/');
    define('WEB_INDEX',WEB_URL.'index.php?s=/');
    define('B2CSERVICE_INDEX',B2CSERVICE_URL.'index.php?s=/');
    define('ORDER_INDEX',ORDER_URL.'index.php?s=/');
    define('MY_INDEX',MY_URL.'index.php?s=/');
    define('SITE_DATA',B2CSERVICE_URL.'Data/');
	define('SITE_PUBLIC',ROOT_URL.'demand/');
	define('__PUBLIC__',B2CSERVICE_URL."Public/");
	define('B2CSERVICE_PUBLIC',B2CSERVICE_URL."Public/");
	define('B2CSERVICE_APIS',B2CSERVICE_URL."apis/");
	//配置
	define('B2CSERVICE_PATH', dirname(__FILE__));
	define('APP_PATH', B2CSERVICE_PATH.'/B2CService/');
	require(APP_PATH.'Common/LitteFunction.php');
	require(APP_PATH.'Common/B2CFunction.php');
	
	
	//其他
    define('NHORDER_INDEX',B2CSERVICE_URL.'IndexNHOrder/index.php?s=/');
	
	//参团游线路主栏目ID
    define('A_XIANLU_TYPEID', 17);
	//参团游线路频道模型ID
    define('A_XIANLU_CHANNEL', 7);
	//参团游线路附表ID
    define('A_XIANLU_ADDONARTICLE', 'cty_addon7');
	
	//自由行线路主栏目ID
    define('B_XIANLU_TYPEID', 18);
	//自由行线路频道模型ID
    define('B_XIANLU_CHANNEL', 7);
	
	//签证主栏目ID
    define('A_QIANZHENG_TYPEID', 34);
	//签证频道模型ID
    define('A_QIANZHENG_CHANNEL', 9);
	//签证附表ID
    define('A_QIANZHENG_ADDONARTICLE', 'glly_qianzheng');
	
	
?>