<?php

class MyAction extends Action{
	
	function index(){
		
//		dump($_COOKIE);
//		dump($_SESSION);
//		dump(1212313);
		
		$u = A("DEDEInfo")->ajax_loginsta('arrary');
		if(empty($u['face']))
			$u['face']=($u['sex']=='女')? 'templets/images/dfgirl.png' : 'templets/images/dfboy.png';
			
				
			
			
	}
	
	
	
	
}
?>