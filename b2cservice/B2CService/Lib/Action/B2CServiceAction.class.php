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
	
	
    public function dopost_gexingdingzhi() {
		C('TOKEN_ON',false);
		$Gexingdingzhi = D("Gexingdingzhi");
		$_REQUEST['datatext'] = serialize($_REQUEST);
		$Gexingdingzhi->mycreate($_REQUEST);
		ShowMsg("提交成功，我们会尽量与您联系。",ROOT_URL);
	}
	
	
}

?>