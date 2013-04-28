<?php

class DEDEInfoAction extends Action{
	//检查用户登录
    public function ajax_loginsta() {
		$u = A("MethodService")->ajax_loginsta();
		$u = json_encode($u);
		echo  $_GET['jsoncallback'].'('.$u.')';
    }
	
    public function ajax_cookie() {
    }
	
		
	

}
?>