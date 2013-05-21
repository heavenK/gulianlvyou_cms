<?php

class DEDEInfoAction extends Action{
	//检查用户登录
    public function ajax_loginsta($returntype,$mid='') {
		$u = A("MethodService")->ajax_loginsta($mid);
		if($returntype == 'arrary')
			return $u;
		$u = json_encode($u);
		echo  $_GET['jsoncallback'].'('.$u.')';
    }
	

}
?>