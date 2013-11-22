<?php
/**
 *
 * 评论
 *
 * @version        $Id: feedback.php 2 15:56 2012年10月30日Z tianya $
 * @package        DedeCMS.Site
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/../include/common.inc.php");

$inquery = "SELECT title FROM `#@__archives` WHERE typeid = 73";
$dsql->SetQuery($inquery);
$dsql->Execute();

$tag = "";

while($arr = $dsql->GetArray()){
	$tag .= "<a href='/plus/gl_list.php?q=".$arr['title']."&searchtype=title&channeltype=7&kwtype=0&xianlu=25,26,18\'>".$arr['title']."</a>&nbsp;";
}
echo 'document.write("'.$tag.'");';

?>