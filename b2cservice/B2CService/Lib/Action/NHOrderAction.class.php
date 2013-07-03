<?php

class NHOrderAction extends Action{
	
    public function index() {
		$u = A("DEDEInfo")->ajax_loginsta('arrary');
		$Dingdan = D("Dingdan");
		$orderall = $Dingdan->where("`mid` = '$u[mid]' AND `status_system` = '1' AND `status_temp` = '开始支付'")->findall();
		foreach($orderall as $v){
			$this->_query_order_byorderID($v['orderID']);
		}
    }
	
	
	
	
    public function _query_order_byorderID($orderID) {
		$order = A("MethodService")->_getdingdan($orderID);
		if(!$order){
			print("<br>Failed!!!"."</br>");
			print("<br>order is not exist!!!"."</br>");
			return false;
		}
		$tOrderNo = $order['orderNo'];
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
			/*
			? 01：订单已建立，等待支付 ? 02：消费者已支付，等待支付结果 ? 03：订单已支付（支付成功） ? 04：订单已结算（支付成功） ? 05：订单已退款 ? 99：订单支付失败
			*/
			if($merchantQueryOrderResult->order->OrderStatus == 03 || $merchantQueryOrderResult->order->OrderStatus == 04){
				$order['status'] = '已支付';
				if(false == $Dingdan->save($order)){
					$msg = iconv("UTF-8","GBK",'<br>已支付成功!!!但订单返回错误！！！</br>');
				}
				else
				$msg = iconv("UTF-8","GBK",'<br>已支付成功!!!</br>');
				print($msg);
				//推送到erp和center
				$order_s = FileGetContents(SERVER_INDEX."Server/dopostOrder/orderID/".$orderID);
				//$order = FileGetContents(CLIENT_INDEX."Client/dopostOrder/orderID/".$v['orderID']);
				return true;
			}
			else{
				$msg = iconv("UTF-8","GBK",'<br>订单支付失败!!!</br>');
				print($msg);
			}
		}
		else
		{
			print("<br>Failed!!!"."</br>");
			print("<br>return code:".$merchantQueryOrderResult->returnCode."</br>"); 
			print("<br>Error Message:".$merchantQueryOrderResult->ErrorMessage."</br>");
			return false;
		}
	}
	
	
	
	
	
	
}
?>





