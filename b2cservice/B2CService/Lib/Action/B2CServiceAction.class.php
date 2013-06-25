<?php

class B2CServiceAction extends Action{
	
    public function getorder() {
		$orderID = $_REQUEST['orderID'];
		$Dingdan = D("Dingdan");
		$order = $Dingdan->relation("joinerlist")->where("`orderID` = '$orderID'")->find();
		if(!$order){
			$returndata['msg'] = '订单获取失败！';
			$returndata['error'] = 'true';
			echo serialize($returndata);
		}
		else
			echo serialize($order);
		
	}
	
	
}

?>