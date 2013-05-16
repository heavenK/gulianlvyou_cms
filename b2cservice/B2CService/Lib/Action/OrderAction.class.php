<?php

class OrderAction extends CommonMyAction{
	
	function index(){
		$this->assign("mark",'我的订单');
		$u = A("DEDEInfo")->ajax_loginsta('arrary');
		if(empty($u['face']))
			$u['face']=($u['sex']=='女')? 'templets/images/dfgirl.png' : 'templets/images/dfboy.png';
		$this->assign("user",$u);
		//订单列表
		$Dingdan = D("Dingdan");
		$orderall = $Dingdan->where("`mid` = '$u[mid]' AND `status_system` = '1'")->order("time desc")->findall();
		$this->assign("orderall",$orderall);
		$this->display('My:orderlist');
	}
	
	
    public function book1() {
		if($_REQUEST['orderID']){
			$order = A("MethodService")->_getdingdan($_REQUEST['orderID']);
			if(!$order){
				echo "订单不存在！！";
				exit;
			}
			$_REQUEST['chanpinID'] = $order['serverdataID'];
			$_REQUEST['chengrenshu'] = $order['chengrenshu'];
			$_REQUEST['ertongshu'] = $order['ertongshu'];
			$this->assign("chengrenshu",$_REQUEST['chengrenshu']);
			$this->assign("ertongshu",$_REQUEST['ertongshu']);
			$this->assign("order",$order);
		}
		$chanpin = A("MethodService")->_checkchanpin($_REQUEST['chanpinID']);
		if(false === $chanpin){
			echo "产品不存在或已经停止销售！！";
			exit;
		}
		$xianlu = unserialize($chanpin['xianlulist']['datatext']);
		$zongjia = $chanpin['adult_price']*$_REQUEST['chengrenshu']+$chanpin['child_price']*$_REQUEST['ertongshu'];
		$this->assign("zongjia",$zongjia);
		$this->assign("zituan",$chanpin);
		$this->assign("xianlu",$xianlu);
		$this->display();
	}
	
    public function dopostbook1() {
		if($_REQUEST['chanpinID']){
			$chanpin = A("MethodService")->_checkchanpin($_REQUEST['chanpinID']);
			if(false === $chanpin){
				echo "产品不存在或已经停止销售！！";
				exit;
			}
			$xianlu = unserialize($chanpin['xianlulist']['datatext']);
			//提交到订单
			$rows = $_REQUEST;
			$rows['serverdataID'] = $_REQUEST['chanpinID'];
			$rows['title_copy'] = $chanpin['title_copy'];
			$rows['chufadi_copy'] = $xianlu['chufadi'];
			$rows['tianshu_copy'] = $xianlu['tianshu'];
			$rows['chutuanriqi_copy'] = $chanpin['chutuanriqi'];
			$rows['tuanhao_copy'] = $chanpin['tuanhao'];
			$rows['chengrenshu'] = $_REQUEST['chengrenshu'];
			$rows['ertongshu'] = $_REQUEST['ertongshu'];
			$rows['status'] = '准备中';
			$rows['price'] = $chanpin['adult_price']*$_REQUEST['chengrenshu']+$chanpin['child_price']*$_REQUEST['ertongshu'];
			$rows['orderID'] = MakeOrders($rows['serverdataID']);
			$redirect_rul = ORDER_INDEX.'Order/book2/orderID/'.$rows['orderID'];
		}
		if($_REQUEST['orderID']){
			$order = A("MethodService")->_getdingdan($_REQUEST['orderID']);
			if(!$order){
				echo "订单不存在！！";
				exit;
			}
			$rows['id'] = $order['id'];
			$rows['orderID'] = $order['orderID'];
			$rows['__hash__'] = $_REQUEST['__hash__'];
			$redirect_rul = ORDER_INDEX.'Order/book3/orderID/'.$rows['orderID'];
		}
		$rows['lxr_name'] = $_REQUEST['lxr_name'];
		$rows['lxr_telnum'] = $_REQUEST['lxr_telnum'];
		$rows['lxr_email'] = $_REQUEST['lxr_email'];
		if($_REQUEST['telservice'])
		$rows['telservice'] = 1;
		else
		$rows['telservice'] = 0;
		$Dingdan = D("Dingdan");
		if(false !== $Dingdan->mycreate($rows)){
			redirect($redirect_rul);
		}
		else{
			echo 'error!';
		}
	}
	
	
    public function book2() {
		$order = A("MethodService")->_getdingdan($_REQUEST['orderID']);
		if(!$order){
			echo "订单不存在！！";
			exit;
		}
		$DingdanJoiner = D("DingdanJoiner");
		$joinerall = $DingdanJoiner->where("`dingdanID` = '$order[id]'")->findall();
		$this->assign("joinerall",$joinerall);
		$chanpin = A("MethodService")->_checkchanpin($order['serverdataID']);
		if(false === $chanpin){
			echo "产品不存在或已经停止销售！！";
			exit;
		}
		$order['zongjia'] = $order['chengrenshu']*$chanpin['adult_price']+$order['ertongshu']*$order['child_price'];
		$this->assign("order",$order);
		$this->assign("zituan",$chanpin);
		$this->assign("xianlu",$xianlu);
		$this->display();
	}
	
	
    public function dopostbook2() {
		$order = A("MethodService")->_getdingdan($_REQUEST['orderID']);
		if(!$order){
			echo "订单不存在！！";
			exit;
		}
		$DingdanJoiner = D("DingdanJoiner");
		 // 手动进行令牌验证
		 if (!$DingdanJoiner->autoCheckToken($_REQUEST)){
			 // 令牌验证错误
			echo "token error!!!";
			return false;
		 }
		 else{
			C('TOKEN_ON',false);
		 }
		$DingdanJoiner->startTrans();
		for($i = 0; $i < $order['chengrenshu']+$order['ertongshu'];$i++){
			if(false === A("MethodService")->_createDingdanJoiner($DingdanJoiner,$_REQUEST,$i)){
				$DingdanJoiner->rollback();
				echo "error!!!";
				return false;
			}
		}
		$DingdanJoiner->commit();
		//订单状态改变
		$Dingdan = D("Dingdan");
		$order['status'] = '等待支付'; 
		$Dingdan->save($order);
		//保存到常用联系人
		redirect(ORDER_INDEX."Order/book3/orderID/".$_REQUEST['orderID']);
		
	}
	
	
    public function book3() {
		$order = A("MethodService")->_getdingdan($_REQUEST['orderID']);
		if(!$order){
			echo "订单不存在！！";
			exit;
		}
		$chanpin = A("MethodService")->_checkchanpin($order['serverdataID']);
		if(false === $chanpin){
			echo "产品不存在或已经停止销售！！";
			exit;
		}
		$this->assign("chanpin",$chanpin);
		$order['zongjia'] = $order['chengrenshu']*$chanpin['adult_price']+$order['ertongshu']*$order['child_price'];
		$DingdanJoiner = D("DingdanJoiner");
		$joinerall = $DingdanJoiner->where("`dingdanID` = '$order[id]'")->findall();
		$this->assign("order",$order);
		$this->assign("joinerall",$joinerall);
		//ip
		$this->assign("ip",real_ip());
		
		$this->display();
		
	}
	
	
	function MerchantPaymant(){
		require(B2CSERVICE_URL."apis/nh/b2c01/api.php");
		$add = "http://www.dlgulian.com:8080/axis/services/B2CWarpper?wsdl";
		$tOrderNo = $_POST['OrderNo'];
		$tExpiredDate = $_POST['ExpiredDate'];
		$tOrderDesc = $_POST['OrderDesc'];
		$tOrderDate = $_POST['OrderDate'];
		$tOrderTime = $_POST['OrderTime'];
		$tOrderAmountStr = $_POST['OrderAmount'];
		$tOrderURL = $_POST['OrderURL'];
		$tBuyIP = $_POST['BuyIP'];
		$tProductType = $_POST['ProductType'];
		$tPaymentType = $_POST['PaymentType'];
		$tNotifyType = $_POST['NotifyType'];
		$tResultNotifyURL = $_POST['ResultNotifyURL'];
		$tMerchantRemarks = $_POST['MerchantRemarks'];
		$tPaymentLinkType = $_POST['PaymentLinkType'];
		$tTotalCount = $_POST['TotalCount'];
		
		$tOrderItems=array();
		for($i=0;$i<$tTotalCount;$i++)
		{
	//		print("<br>".$_POST['productname'][$i]."</br>");
			$tOrderItems[]=array($_POST['productid'][$i], $_POST['productname'][$i], $_POST['uniteprice'][$i], $_POST['qty'][$i]);
		}
		
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
	//		print("<br>Failed!!!"."</br>");
	//		print("<br>return code:".$merchantPaymentResult->returnCode."</br>"); 
	//		print("<br>Error Message:".iconv("GBK","UTF-8",$merchantPaymentResult->ErrorMessage)."</br>");
			//易宝支付失败更改订单号
			if($tPaymentType == 5){
				API_change_orderID(1);
			}
				
	
		}
	
/*	
		<script language=javascript>
		//	支付请求页面跳转
			var redirectURL="<?=$PaymentURL?>";
			if(redirectURL!=null&&redirectURL!="")
			{
				location.href='<?=$PaymentURL?>';
			}
		</script> 
        
*/        
	}
	
	
	
	
	
}
?>