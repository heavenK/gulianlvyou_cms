<?php

class DISCUZInfoAction extends Action{
	//检查用户登录
    public function ajax_loginsta($returntype) {
		
    }
	
    public function ajax_cookie() {
		
    }
	
	//接口获得论坛用户头像	
    public function interface_getUserFace() {
		$mid = $_REQUEST['mid'];
		if(!$mid)
		return false;
		$u = A("DEDEInfo")->ajax_loginsta('arrary',$mid);
//		if(empty($u['face']))
//			$u['face']=($u['sex']=='女')? 'templets/images/dfgirl.png' : 'templets/images/dfboy.png';
		$face = BBS_URL.'uc_server/avatar.php?uid='.$u['uc']['uid'];
		$face = serialize($face);
		echo $face;
    }

}
?>