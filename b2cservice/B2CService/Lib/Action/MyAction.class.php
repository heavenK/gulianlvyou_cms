<?php

class MyAction extends CommonMyAction{
	
	function index(){
		$u = A("DEDEInfo")->ajax_loginsta('arrary');
		if(empty($u['face']))
			$u['face']=($u['sex']=='女')? 'templets/images/dfgirl.png' : 'templets/images/dfboy.png';
		$this->assign("user",$u);
		dump($u);	
		$this->display();
	}
	
	
	
	
	
	
	
	
}
?>