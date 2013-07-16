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
	
	function showheader(){
		$tips = $this->_getadstips();
		$this->assign("tips",$tips);
		$this->display('header');
	}
	
	
    public function book1() {
		if($_REQUEST['orderID']){
			$order = A("MethodService")->_getdingdan($_REQUEST['orderID']);
			if(!$order){
				echo "订单不存在！！";
				exit;
			}
			if($order['status'] == '已支付'){
				ShowMsg("已支付不允许修改");
				exit;
			}
			$_REQUEST['chanpinID'] = $order['serverdataID'];
			$_REQUEST['chengrenshu'] = $order['chengrenshu'];
			$_REQUEST['ertongshu'] = $order['ertongshu'];
			$this->assign("chengrenshu",$_REQUEST['chengrenshu']);
			$this->assign("ertongshu",$_REQUEST['ertongshu']);
			$this->assign("order",$order);
		}
		
		if($_REQUEST['chanpintype'] == '签证'){
			$chanpin = A("MethodService")->_checkchanpin_qianzheng($_REQUEST['chanpinID']);
			if(false === $chanpin){
				echo "产品不存在或已经停止销售！！";
				exit;
			}
			$DEDEAddonarticle = D(A_QIANZHENG_ADDONARTICLE);//自定义模型文章附表
			$aid = $_REQUEST['aid'];
			$qianzheng_info = $DEDEAddonarticle->where("`aid` = '$aid'")->find();
			$this->assign("qianzheng",$chanpin);
			$this->assign("qianzheng_info",$qianzheng_info);
			$zongjia = $chanpin['shoujia'];
			$this->assign("zongjia",$zongjia);
			$this->display('book1_qianzheng');
		}
		else{
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
	}
	
    public function dopostbook1() {
		if($_REQUEST['chanpinID']){
			if($_REQUEST['chanpintype'] == '签证'){
				$chanpin = A("MethodService")->_checkchanpin_qianzheng($_REQUEST['chanpinID']);
				if(false === $chanpin){
					echo "产品不存在或已经停止销售！！";
					exit;
				}
				//提交到订单
				$rows = $_REQUEST;
				$rows['serverdataID'] = $_REQUEST['chanpinID'];
				$rows['type'] = '签证';
				$rows['title_copy'] = $chanpin['title'];
				$rows['chutuanriqi_copy'] = $chanpin['chutuanriqi'];
				$rows['chengrenshu'] = 1;
				$rows['status'] = '等待支付';
				$rows['price'] = $chanpin['shoujia'];
				$rows['adult_price'] = $chanpin['shoujia'];
				$rows['child_price'] = $chanpin['ertongshoujia'];
				$rows['orderID'] = MakeOrders($rows['serverdataID'],'GL');
				$rows['orderNo'] = MakeOrders($rows['serverdataID']);
				$redirect_rul = ORDER_INDEX.'Order/book2/orderID/'.$rows['orderID'];
			}
			else{
				$chanpin = A("MethodService")->_checkchanpin($_REQUEST['chanpinID']);
				if(false === $chanpin){
					echo "产品不存在或已经停止销售！！";
					exit;
				}
				$xianlu = unserialize($chanpin['xianlulist']['datatext']);
				//提交到订单
				$rows = $_REQUEST;
				$rows['serverdataID'] = $_REQUEST['chanpinID'];
				$rows['type'] = '标准';
				$rows['title_copy'] = $chanpin['title_copy'];
				$rows['chufadi_copy'] = $xianlu['chufadi'];
				$rows['tianshu_copy'] = $xianlu['tianshu'];
				$rows['chutuanriqi_copy'] = $chanpin['chutuanriqi'];
				$rows['tuanhao_copy'] = $chanpin['tuanhao'];
				$rows['chengrenshu'] = $_REQUEST['chengrenshu'];
				$rows['ertongshu'] = $_REQUEST['ertongshu'];
				$rows['status'] = '准备中';
				$rows['adult_price'] = $chanpin['adult_price'];
				if($rows['adult_price'] == NULL || $rows['adult_price'] == 0){
					dump($rows);
					echo "数据错误！！";
					exit;
				}
				$rows['child_price'] = $chanpin['child_price'];
				if($rows['adult_price'] == NULL){
					dump($rows);
					echo "数据错误！！";
					exit;
				}
				$rows['price'] = $chanpin['adult_price']*$_REQUEST['chengrenshu']+$chanpin['child_price']*$_REQUEST['ertongshu'];
				if($rows['price'] == NULL || $rows['price'] < 0 || $rows['price'] == 0){
					dump($rows);
					echo "数据错误！！";
					exit;
				}
				$rows['orderID'] = MakeOrders($rows['serverdataID'],'GL');
				$redirect_rul = ORDER_INDEX.'Order/book2/orderID/'.$rows['orderID'];
			}
		}
		if($_REQUEST['orderID']){
			$order = A("MethodService")->_getdingdan($_REQUEST['orderID']);
			if(!$order){
				echo "订单不存在！！";
				exit;
			}
			if($order['status'] == '已支付'){
				ShowMsg("已支付不允许修改");
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
		$rows['remark'] = $_REQUEST['remark'];
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
		if($order['type'] == '签证'){
			$chanpin = A("MethodService")->_checkchanpin_qianzheng($order['serverdataID']);
			if(false === $chanpin){
				echo "产品不存在或已经停止销售！！";
				exit;
			}
			$order['zongjia'] = $chanpin['shoujia'];
			$this->assign("qianzheng_info",$chanpin);
			$this->assign("order",$order);
			$this->display('book2_qianzheng');
		}
		else{
			if($order['status'] == '已支付'){
				ShowMsg("已支付不允许修改",ORDER_INDEX);
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
	}
	
	
    public function dopostbook2() {
		$order = A("MethodService")->_getdingdan($_REQUEST['orderID']);
		if(!$order){
			ShowMsg("订单不存在");
			exit;
		}
		if($order['status'] == '已支付'){
			ShowMsg("已支付不允许修改");
			exit;
		}
		$DingdanJoiner = D("DingdanJoiner");
		 // 手动进行令牌验证
		 if (!$DingdanJoiner->autoCheckToken($_REQUEST)){
			 // 令牌验证错误
			echo "token error!!!";
			exit;
		 }
		 else{
			C('TOKEN_ON',false);
		 }
		$DingdanJoiner->startTrans();
		for($i = 0; $i < $order['chengrenshu']+$order['ertongshu'];$i++){
			if(false === A("MethodService")->_createDingdanJoiner($DingdanJoiner,$_REQUEST,$i)){
				$DingdanJoiner->rollback();
				echo "error!!!";
				exit;
			}
		}
		$DingdanJoiner->commit();
		//订单状态改变
		$Dingdan = D("Dingdan");
		$order['status'] = '等待支付'; 
		$Dingdan->save($order);
		//保存到常用联系人
		A("MethodService")->_createUserJoiner($order);
		redirect(ORDER_INDEX."Order/book3/orderID/".$_REQUEST['orderID']);
	}
	
	
    public function book3() {
		$order = A("MethodService")->_getdingdan($_REQUEST['orderID']);
		if(!$order){
			redirect(ORDER_URL);
			echo "order is not find";
//			echo "订单不存在！！";
			exit;
		}
		if($order['type'] == '签证'){
			$redirect_rul = ORDER_INDEX.'Order/book2/orderID/'.$order['orderID'];
			redirect($redirect_rul);
		}
		else{
			$chanpin = A("MethodService")->_checkchanpin($order['serverdataID']);
			if(false === $chanpin){
				redirect(ORDER_URL);
				echo "product is not selling or out exist";
//				echo "产品不存在或已经停止销售！！";
				exit;
			}
		}
		$this->assign("chanpin",$chanpin);
		$order['zongjia'] = $order['chengrenshu']*$chanpin['adult_price']+$order['ertongshu']*$order['child_price'];
		$DingdanJoiner = D("DingdanJoiner");
		$joinerall = $DingdanJoiner->where("`dingdanID` = '$order[id]'")->findall();
		$this->assign("order",$order);
		$this->assign("joinerall",$joinerall);
		
		$this->display();
		
	}
	
	function queryOrder(){
		$orderID = $_REQUEST['orderID'];
		$dingdan = A("NHOrder")->_query_order_byorderID($orderID,0);
		if($dingdan){
			$_REQUEST['msg'] = '支付成功';
			$_REQUEST['msg'] = iconv("UTF-8","GBK",$_REQUEST['msg']);
			print("<br>Message:".$_REQUEST['msg']."</br>");
			exit;
		}
		else{
			$_REQUEST['msg'] = '支付失败';
			$_REQUEST['msg'] = iconv("UTF-8","GBK",$_REQUEST['msg']);
			print("<br>Failed!!!"."</br>");
			print("<br>Error Message:".$_REQUEST['msg']."</br>");
			exit;
		}
	}
	
	function helpOrder(){
//		$orderID = $_REQUEST['orderID'];
//		$dingdan = A("NHOrder")->_query_order_byorderID($orderID);
//		if($dingdan){
//			redirect($redirect_rul);
//		}
//		else{
//			$Dingdan = D("Dingdan");
//			$order['status_temp'] = '支付帮助';
//			$dingdan = $Dingdan->where("`orderID` = '$order[orderID]'")->save($order);
//			redirect($redirect_rul);
//		}
	}
	
	function MerchantPaymant(){
		$orderID = $_REQUEST['orderID'];
		//支付前进行订单查询
		if(A("NHOrder")->_query_order_byorderID($orderID,0)){
			redirect(ORDER_INDEX);
		}
		require_once(B2CSERVICE_PATH."/apis/nh/b2c01/api.php");
		//$add = "http://www.dlgulian.com:8080/axis/services/B2CWarpper?wsdl";
		$add = "http://www.gulianlvyou.com:8080/axis/services/B2CWarpper?wsdl";
		//检查订单
		$info = A("MethodService")->_check_dingdan_valid($orderID);
		if(false === $info){
			$_REQUEST['msg'] = '订单已失效或产品已下架';
			$_REQUEST['msg'] = iconv("UTF-8","GBK",$_REQUEST['msg']);
//			$this->ajaxReturn($_REQUEST, '操作失败123！', 0);
			print("<br>Failed!!!"."</br>");
			print("<br>Error Message:".$_REQUEST['msg']."</br>");
			print("<br>Point0</br>");
			exit;
		}
		else{
			$order = $info['order'];
			$chanpin = $info['chanpin'];
			if($order['status'] != '等待支付'){
				$_REQUEST['msg'] = '此订单不允许再支付';
				$_REQUEST['msg'] = iconv("UTF-8","GBK",$_REQUEST['msg']);
//				$this->ajaxReturn($_REQUEST, '操作失败234！', 0);
				print("<br>Failed!!!"."</br>");
				print("<br>Error Message:".$_REQUEST['msg']."</br>");
				print("<br>Point1</br>");
				exit;
			}
		}
		//数据填充
		$tOrderNo = $order['OrderNo'];//副ID
		$tExpiredDate = 30;
		if($order['type'] != '签证')
			$tOrderDesc = "线路：".$order['title_copy']."/团号：".$order['tuanhao']."/联系人：".$order['lxr_name'];
		else
			$tOrderDesc = "签证：".$order['title_copy']."/联系人：".$order['lxr_name'];
		$tOrderDate = date("Y/m/d",time());
		$tOrderTime = date("H:i:s",time());
		$tOrderAmountStr = 0.01;
		$tOrderAmountStr = $order['price'];
		$tOrderURL = ORDER_INDEX.'Order/book3/orderID/'.$orderID;//必填
		$tBuyIP = real_ip();
		$tProductType = 1;
		$tPaymentType = $_POST['PaymentType'];
		$tNotifyType = 1;//设定支付结果通知方式（必要信息）0：URL页面通知 1：服务器通知
		$tResultNotifyURL = NHORDER_INDEX;//这货不能带参数
		$tMerchantRemarks = '';//商户备注信息
		$tPaymentLinkType = 1;//设定支付接入方式（必要信息） 注意：目前支持三种接入方式，Internet网络接入，Mobile网络接入，数字电视网络接入，不同的支付方式会返回不同的支付处理页面。
		$tTotalCount = $order['chengrenshu']+$order['ertongshu'];
		$tOrderItems=array();
		$DingdanJoiner = D("DingdanJoiner");
		$joinerall = $DingdanJoiner->where("`dingdanID` = '$order[id]'")->findall();
		foreach($joinerall as $v){
			if($v['manorchild'] == '儿童') 
				$itemprice = $chanpin['child_price'];
			else 
				$itemprice = $chanpin['adult_price']; 
			$tOrderItems[]=array($order['serverdataID'], $order['title_copy'], $itemprice, '1');
		}
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
			//检查订单合法性
			$tOrderNo = API_change_orderNo($orderID);
			$merchantPaymentRequest = new MerchantPaymentRequest($tOrderNo,$tExpiredDate,$tOrderDesc,$tOrderDate,$tOrderTime,$tOrderAmountStr,$tOrderURL,$tBuyIP,$tProductType,$tPaymentType,$tNotifyType,$tResultNotifyURL,$tMerchantRemarks,$tPaymentLinkType,$tOrderItems);
			$merchantPayment = new MerchantPayment($add,$merchantPaymentRequest);
			$merchantPaymentResult = $merchantPayment->invoke();
			if($merchantPaymentResult->isSucess==TRUE)
			{
				$PaymentURL = $merchantPaymentResult->paymentURL;
			}
			else{
//				$_REQUEST['msg'] = iconv("GBK","UTF-8",$merchantPaymentResult->ErrorMessage);
//				$this->ajaxReturn($_REQUEST, '操作失败345！', 0);
				print("<br>Failed!!!"."</br>");
				print("<br>return code:".$merchantPaymentResult->returnCode."</br>"); 
				print("<br>Error Message:".$merchantPaymentResult->ErrorMessage."</br>");
				print("<br>Point2</br>");
				exit;
			}
		}
		
		//修改订单状态，标记支付动作
		A("MethodService")->_change_order_tempstatus($orderID,'开始支付');
		$_REQUEST['PaymentURL'] = $PaymentURL;
//		$this->ajaxReturn($_REQUEST, '保存成功！', 1);
		echo '<script language=javascript>var redirectURL="'.$PaymentURL.'";if(redirectURL!=null&&redirectURL!=""){location.href="'.$PaymentURL.'";}</script> ';
	}
	
	
	//广告
	function _getadstips(){
		$DEDEArchives = D("DEDEArchives");//文章主表
		$tips = $DEDEArchives->where("`typeid` = '73'")->order('id desc')->findall();
		return $tips;
	}
}
?>





