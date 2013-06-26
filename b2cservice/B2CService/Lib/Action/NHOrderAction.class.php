<?php

class NHOrderAction extends Action{
	
    public function index() {
		$u = A("DEDEInfo")->ajax_loginsta('arrary');
		$Dingdan = D("Dingdan");
		$orderall = $Dingdan->where("`mid` = '$u[mid]' AND `status_system` = '1' AND `status_temp` = '开始支付'")->findall();
		foreach($orderall as $v){
			$this->_query_order($v['orderID']);
		}
    }
	
	
	
	
    public function _query_order($tOrderNo) {
		require_once(B2CSERVICE_PATH."/apis/nh/b2c01/api.php");
		$add = "http://www.gulianlvyou.com:8080/axis/services/B2CWarpper?wsdl";
		$Dingdan = D("Dingdan");
		$tQueryType = 0;//0：状态查询； 1：详细查询
		//$merchantQueryOrder = new MerchantQueryOrder($add,$tOrderNo,$tQueryType);
		$merchantQueryOrderRequest = new MerchantQueryOrderRequest($tOrderNo,$tQueryType);
		$merchantQueryOrder = new MerchantQueryOrder($add,$merchantQueryOrderRequest);
		$merchantQueryOrderResult = $merchantQueryOrder->invoke();
		//$merchantQueryOrder->showResult();
		//显示结果
		if($merchantQueryOrderResult->isSucess==TRUE)
		{
			$v['status'] = '已支付';
			if(false == $Dingdan->where("`orderID` = '$tOrderNo'")->save($v)){
				$msg = iconv("UTF-8","GBK",'<br>支付失败!!!</br>');
			}
			else
			$msg = iconv("UTF-8","GBK",'<br>已支付成功!!!</br>');
			print($msg);
			//推送到erp和center
			$order_s = FileGetContents(SERVER_INDEX."Server/dopostOrder/orderID/".$v['orderID']);
			//$order = FileGetContents(CLIENT_INDEX."Client/dopostOrder/orderID/".$v['orderID']);
		}
		else
		{
			print("<br>Failed!!!"."</br>");
			print("<br>return code:".$merchantQueryOrderResult->returnCode."</br>"); 
			print("<br>Error Message:".$merchantQueryOrderResult->ErrorMessage."</br>");
		}
	}
	
	
	
	
	
	
}
?>





