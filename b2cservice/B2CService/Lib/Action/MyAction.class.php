<?php

class MyAction extends CommonMyAction{
	
	function index(){
		$u = A("DEDEInfo")->ajax_loginsta('arrary');
		if(empty($u['face']))
			$u['face']=($u['sex']=='女')? 'templets/images/dfgirl.png' : 'templets/images/dfboy.png';
		$this->assign("user",$u);
		//订单列表
		$Dingdan = D("Dingdan");
		$orderall = $Dingdan->where("`mid` = '$u[mid]' AND `status_system` = '1'")->findall();
		$this->assign("orderall",$orderall);
		$this->display();
	}
	
	
	
	
	
	
	
	
}
?>