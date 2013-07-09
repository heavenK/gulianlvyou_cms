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
	
	
	
	//查询
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
	
	
	
	//查询
    public function _interface_query_order() {
		$tOrderNo = $_REQUEST['orderNo'];
		$tOrderID = $_REQUEST['orderID'];
		if(!$tOrderNo){
			$Dingdan = D("Dingdan");
			$dingdan = $Dingdan->where("`orderID` = '$tOrderID'")->find();
			$tOrderNo = $dingdan['orderNo'];
		}
		if(!$tOrderNo){
			$returndata['msg'] = "订单查询失败！订单编号无效！";
			$returndata['error'] = 'true';
			echo serialize($returndata);
			exit;
		}
		require_once(B2CSERVICE_PATH."/apis/nh/b2c01/api.php");
		$add = "http://www.gulianlvyou.com:8080/axis/services/B2CWarpper?wsdl";
		$Dingdan = D("Dingdan");
		$tQueryType = 1;//0：状态查询； 1：详细查询
		$merchantQueryOrderRequest = new MerchantQueryOrderRequest($tOrderNo,$tQueryType);
		$merchantQueryOrder = new MerchantQueryOrder($add,$merchantQueryOrderRequest);
		$merchantQueryOrderResult = $merchantQueryOrder->invoke();
		//显示结果
		if($merchantQueryOrderResult->isSucess==TRUE)
		{
			/*
			? 01：订单已建立，等待支付 ? 02：消费者已支付，等待支付结果 ? 03：订单已支付（支付成功） ? 04：订单已结算（支付成功） ? 05：订单已退款 ? 99：订单支付失败
			*/
			if($merchantQueryOrderResult->order->OrderStatus == 03 || $merchantQueryOrderResult->order->OrderStatus == 04){
				$order['zhifu'] = $merchantQueryOrderResult->order->PayAmount;
				$order['shijian'] = $merchantQueryOrderResult->order->OrderDate.' '.$merchantQueryOrderResult->order->OrderTime;
				$order['miaoshu'] = $merchantQueryOrderResult->order->OrderDesc;
				$order['url'] = $merchantQueryOrderResult->order->OrderURL;
				$count = count($merchantQueryOrderResult->order->OrderItems);
				$order['itemcount'] = $count;
				for ($i = 0; $i < $count; $i++) 
				{	
					$item = $merchantQueryOrderResult->order->OrderItems[$i];
					$order['list'][$i]['ProductName'] = $item->ProductName;
					$order['list'][$i]['Qty'] = $item->Qty;
					$order['list'][$i]['UnitPrice'] = $item->UnitPrice;
				}
				echo serialize($order);
				exit;	
			}
			else{
				$returndata['msg'] = "此订单未支付成功！";
				$returndata['error'] = 'true';
				echo serialize($returndata);
				exit;
			}
		}
		else
		{
			$returndata['msg'] = "订单查询失败！错误代码：".$merchantQueryOrderResult->returnCode."<br>错误内容：".$merchantQueryOrderResult->ErrorMessage;
			$returndata['error'] = 'true';
			echo serialize($returndata);
			exit;
		}
	}
	
	
	
	
	
	
	
	
	
	
	//支付测试
	function MerchantPaymant_test(){
		require_once(B2CSERVICE_PATH."/apis/nh/b2c01/api.php");
		$add = "http://www.gulianlvyou.com:8080/axis/services/B2CWarpper?wsdl";
		//数据填充
		$tOrderNo = 'CSTC'.time().rand(100,200);
		$tExpiredDate = 30;
		$tOrderDesc = "测试填充";
		$tOrderDate = date("Y/m/d",time());
		$tOrderTime = date("H:i:s",time());
		$tOrderAmountStr = 0.01;
		$tOrderURL = '测试地址';
		$tBuyIP = real_ip();
		$tProductType = 1;
		$tPaymentType = $_REQUEST['PaymentType'];
		$tNotifyType = 1;//设定支付结果通知方式（必要信息）0：URL页面通知 1：服务器通知
		$tResultNotifyURL = '';//这货不能带参数
		$tMerchantRemarks = '';//商户备注信息
		$tPaymentLinkType = $_REQUEST['PaymentLinkType'];//设定支付接入方式（必要信息） 注意：目前支持三种接入方式，Internet网络接入，Mobile网络接入，数字电视网络接入，不同的支付方式会返回不同的支付处理页面。
		$tTotalCount = 1;
		$tOrderItems=array();
		//通信
		$merchantPaymentRequest = new MerchantPaymentRequest($tOrderNo,$tExpiredDate,$tOrderDesc,$tOrderDate,$tOrderTime,$tOrderAmountStr,$tOrderURL,$tBuyIP,$tProductType,$tPaymentType,$tNotifyType,$tResultNotifyURL,$tMerchantRemarks,$tPaymentLinkType,$tOrderItems);
		$merchantPayment = new MerchantPayment($add,$merchantPaymentRequest);
		$merchantPaymentResult = $merchantPayment->invoke();
		//显示结果
		if($merchantPaymentResult->isSucess==TRUE)
		{
			$PaymentURL = $merchantPaymentResult->paymentURL;
		}
		else
		{
			echo '<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
			print("<br>Failed!!!"."</br>");
			print("<br>return code:".$merchantPaymentResult->returnCode."</br>"); 
			print("<br>Error Message:".iconv("GBK","UTF-8",$merchantPaymentResult->ErrorMessage)."</br>");
		}
		echo '<script language=javascript>var redirectURL="'.$PaymentURL.'";if(redirectURL!=null&&redirectURL!=""){location.href="'.$PaymentURL.'";}</script> ';
	}
	
	
	
	
	
	
	
	
	
	
}
?>





