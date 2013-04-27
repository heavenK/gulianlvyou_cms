<?php

class DISCUZInfoAction extends Action{
	//检查用户登录
    public function ajax_loginsta($returntype) {
		$u = A("MethodService")->ajax_loginsta();
		if($returntype == 'arrary')
			return $u;
		$u = json_encode($u);
		echo  $_GET['jsoncallback'].'('.$u.')';
    }
	
    public function ajax_cookie() {
    }
	
		
	

}
?>