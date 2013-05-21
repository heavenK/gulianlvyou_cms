<?php

require(dirname(__FILE__).'/../define2.inc.php');
define('SITE_INDEX',ORDER_URL.'index.php?s=/');
define('SITE_URL',ORDER_URL);
define('__T_PUBLIC__',ORDER_URL.'Public');
define('DEFAULT_ACTION', 'Order');
define('APP_DEBUG', true);		// Use debug 

require(THINK_PATH.'ThinkPHP.php');

?>