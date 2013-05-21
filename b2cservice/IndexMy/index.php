<?php

require(dirname(__FILE__).'/../define2.inc.php');
define('SITE_INDEX',MY_URL.'index.php?s=/');
define('SITE_URL',MY_URL);
define('__T_PUBLIC__',MY_URL.'Public');
define('DEFAULT_ACTION', 'My');
define('APP_DEBUG', true);		// Use debug 

require(THINK_PATH.'ThinkPHP.php');

?>