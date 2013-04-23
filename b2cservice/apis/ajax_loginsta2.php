<?php
require_once(dirname(__FILE__).'/../../include/common.inc.php');
require_once(DEDEINC.'/memberlogin.class.php');
$cfg_ml = new MemberLogin($keeptime);
echo "<pre>";
var_dump($_COOKIE);
session_start();
var_dump($_SESSION);

?>


















