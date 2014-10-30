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
				$this->assign("msg_title",'订单不存在！');
				$this->assign("msg_content",'');
				$this->display('xinxi_tishi');
				exit;
			}
			if($order['status'] == '已支付'){
				$this->assign("msg_title",'已支付不允许修改！');
				$this->assign("msg_content",'');
				$this->display('xinxi_tishi');
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
				$this->assign("msg_title",'产品不存在或已经停止销售！');
				$this->assign("msg_content",'');
				$this->display('xinxi_tishi');
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
				$this->assign("msg_title",'产品不存在或已经停止销售！');
				$this->assign("msg_content",'');
				$this->display('xinxi_tishi');
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
				$chanpin = A("MethodService")->_checkchanpin_qianzheng($_REQUEST['chanpinID'],1);
				if(false === $chanpin){
					$this->assign("msg_title",'产品不存在或已经停止销售！');
					$this->assign("msg_content",'');
					$this->display('xinxi_tishi');
					exit;
				}
				//提交到订单
				$rows = $_REQUEST;
				$rows['serverdataID'] = $_REQUEST['chanpinID'];
				$rows['clientdataID'] = $chanpin['clientdataID'];
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
				$chanpin = A("MethodService")->_checkchanpin($_REQUEST['chanpinID'],1);
				if(false === $chanpin){
					$this->assign("msg_title",'产品不存在或已经停止销售！');
					$this->assign("msg_content",'');
					$this->display('xinxi_tishi');
					exit;
				}
				$xianlu = unserialize($chanpin['xianlulist']['datatext']);
				//提交到订单
				$rows = $_REQUEST;
				$rows['serverdataID'] = $_REQUEST['chanpinID'];
				$rows['clientdataID'] = $chanpin['clientdataID'];
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
				$rows['second_confirm'] = $chanpin['second_confirm'];
				if($rows['adult_price'] == NULL || $rows['adult_price'] == 0){
					$this->assign("msg_title",'订单发生错误，请稍候重试！');
					$this->assign("msg_content",'');
					$this->display('xinxi_tishi');
					exit;
				}
				$rows['child_price'] = $chanpin['child_price'];
				if($rows['adult_price'] == NULL){
					$this->assign("msg_title",'订单发生错误，请稍候重试！');
					$this->assign("msg_content",'');
					$this->display('xinxi_tishi');
					exit;
				}
				$rows['price'] = $chanpin['adult_price']*$_REQUEST['chengrenshu']+$chanpin['child_price']*$_REQUEST['ertongshu'];
				if($rows['price'] == NULL || $rows['price'] < 0 || $rows['price'] == 0){
					$this->assign("msg_title",'订单发生错误，请稍候重试！');
					$this->assign("msg_content",'');
					$this->display('xinxi_tishi');
					exit;
				}
				$rows['orderID'] = MakeOrders($rows['serverdataID'],'GL');
				$redirect_rul = ORDER_INDEX.'Order/book2/orderID/'.$rows['orderID'];
			}
		}
		if($_REQUEST['orderID']){
			$order = A("MethodService")->_getdingdan($_REQUEST['orderID']);
			if(!$order){
				$this->assign("msg_title",'订单不存在！');
				$this->assign("msg_content",'');
				$this->display('xinxi_tishi');
				exit;
			}
			if($order['status'] == '已支付'){
				$this->assign("msg_title",'已支付不允许修改！');
				$this->assign("msg_content",'');
				$this->display('xinxi_tishi');
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
			$this->assign("msg_title",'操作失败，请稍候重试！');
			$this->assign("msg_content",'');
			$this->display('xinxi_tishi');
			exit;
		}
	}
	
	
    public function book2() {
		$order = A("MethodService")->_getdingdan($_REQUEST['orderID']);
		if(!$order){
			$this->assign("msg_title",'订单不存在！');
			$this->assign("msg_content",'');
			$this->display('xinxi_tishi');
			exit;
		}
		if($order['type'] == '签证'){
			//设置支付状态
			$chanpin = A("MethodService")->_checkchanpin_qianzheng($order['serverdataID'],1);
			if(false === $chanpin){
				$this->assign("zhifu_status",'false');
			}
			$order['zongjia'] = $chanpin['shoujia'];
			$this->assign("qianzheng_info",$chanpin);
			$this->assign("order",$order);
			$this->display('book2_qianzheng');
		}
		else{
			if($order['status'] == '已支付'){
				$this->assign("msg_title",'已支付不允许修改！');
				$this->assign("msg_content",'');
				$this->display('xinxi_tishi');
				exit;
			}
			$DingdanJoiner = D("DingdanJoiner");
			$joinerall = $DingdanJoiner->where("`dingdanID` = '$order[id]'")->findall();
			$this->assign("joinerall",$joinerall);
			$chanpin = A("MethodService")->_checkchanpin($order['serverdataID']);
			if(false === $chanpin){
				$this->assign("msg_title",'产品不存在或已经停止销售！');
				$this->assign("msg_content",'');
				$this->display('xinxi_tishi');
				exit;
			}
			$order['zongjia'] = $order['chengrenshu']*$chanpin['adult_price']+$order['ertongshu']*$order['child_price'];
			$this->assign("order",$order);
			$this->assign("zituan",$chanpin);
			$this->assign("xianlu",$xianlu);
			
			if($chanpin['second_confirm'] == 1)
				$this->display('book2_second_confirm');
			else
				$this->display();
		}
	}
	
	
    public function dopostbook2() {
		$order = A("MethodService")->_getdingdan($_REQUEST['orderID']);
		if(!$order){
			$this->assign("msg_title",'操作失败，订单不存在！');
			$this->assign("msg_content",'');
			$this->display('xinxi_tishi');
			exit;
		}
		if($order['status'] == '已支付'){
			$this->assign("msg_title",'操作失败，已支付不允许修改！');
			$this->assign("msg_content",'');
			$this->display('xinxi_tishi');
			exit;
		}
		$DingdanJoiner = D("DingdanJoiner");
		 // 手动进行令牌验证
		 if (!$DingdanJoiner->autoCheckToken($_REQUEST)){
			 // 令牌验证错误
			$this->assign("msg_title",'操作失败，请刷新后重试！');
			$this->assign("msg_content",'');
			$this->display('xinxi_tishi');
			exit;
		 }
		 else{
			C('TOKEN_ON',false);
		 }
		$DingdanJoiner->startTrans();
		for($i = 0; $i < $order['chengrenshu']+$order['ertongshu'];$i++){
			if(false === A("MethodService")->_createDingdanJoiner($DingdanJoiner,$_REQUEST,$i)){
				$DingdanJoiner->rollback();
				$this->assign("msg_title",'操作失败，请稍候重试！');
				$this->assign("msg_content",'');
				$this->display('xinxi_tishi');
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
			$this->assign("msg_title",'操作失败，订单不存在！');
			$this->assign("msg_content",'');
			$this->display('xinxi_tishi');
			exit;
		}
		if($order['type'] == '签证'){
			$redirect_rul = ORDER_INDEX.'Order/book2/orderID/'.$order['orderID'];
			redirect($redirect_rul);
		}
		else{
			//设置支付状态
			$chanpin = A("MethodService")->_checkchanpin($order['serverdataID'],1);
			if(false === $chanpin){
				$this->assign("zhifu_status",'false');
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
			$this->assign("msg_title",'支付成功！');
			$this->assign("msg_content",'我们的工作人员会尽快与您联系，您也可以致电我们。');
			$this->display('xinxi_tishi_suc');
			exit;
		}
		else{
			$this->assign("msg_title",'支付失败！');
			$this->assign("msg_content",'如已经扣款，请与我们取得联系，谢谢。');
			$this->display('xinxi_tishi');
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
		/*if(A("NHOrder")->_query_order_byorderID($orderID,0)){
			$this->assign("msg_title",'订单已支付！');
			$this->assign("msg_content",'');
			$this->display('xinxi_tishi');
			exit;
			//redirect(ORDER_INDEX);
		}*/
		$Dingdan = D("Dingdan");
		$order = $Dingdan->where("`orderID` = '$orderID'")->find();
		//检查产品
		if($order['type'] == '签证')
			$chanpin = A("MethodService")->_checkchanpin_qianzheng($order['serverdataID'],1);
		else
			$chanpin = A("MethodService")->_checkchanpin($order['serverdataID'],1);
		if(false === $chanpin){
			$this->assign("msg_title",'支付失败！');
			$this->assign("msg_content",'该订单所属产品已下架或停止销售！');
			$this->display('xinxi_tishi');
			exit;
			//redirect(ORDER_INDEX);
		}
		
		require_once(B2CSERVICE_PATH."/apis/yinlian/quickpay_service.php");
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
		
		
		$param['transType']             = quickpay_conf::CONSUME;  //交易类型，CONSUME or PRE_AUTH
		
		$param['orderAmount']           = (int)$order['price'];;        //交易金额
		$param['orderNumber']           = $orderID; //订单号，必须唯一
		$param['orderTime']             = date('YmdHis');   //交易时间, YYYYmmhhddHHMMSS
		$param['orderCurrency']         = quickpay_conf::CURRENCY_CNY;  //交易币种，CURRENCY_CNY=>人民币
		
		$param['customerIp']            = $_SERVER['REMOTE_ADDR'];  //用户IP
		$param['frontEndUrl']           = NHORDER_INDEX;    //前台回调URL
		$param['backEndUrl']            = NHORDER_INDEX;    //后台回调URL
		
		/* 可填空字段*/
		$param['commodityUrl']          = ORDER_INDEX.'Order/book3/orderID/'.$orderID;  //商品URL
		
		if($order['type'] != '签证')
			$param['commodityName'] = "线路：".$order['title_copy']."/团号：".$order['tuanhao']."/联系人：".$order['lxr_name'];
		else
			$param['commodityName'] = "签证：".$order['title_copy']."/联系人：".$order['lxr_name'];

		//$param['commodityUnitPrice']    = 11000;        //商品单价
		//$param['commodityQuantity']     = 1;            //商品数量
		//
		
		//其余可填空的参数可以不填写
		
		$pay_service = new quickpay_service($param, quickpay_conf::FRONT_PAY);
		$html = $pay_service->create_html();
		
		header("Content-Type: text/html; charset=" . quickpay_conf::$pay_params['charset']);
		echo $html; //自动post表单
		
		
		
		/*require_once(B2CSERVICE_PATH."/apis/nh/b2c01/api.php");
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
		echo '<script language=javascript>var redirectURL="'.$PaymentURL.'";if(redirectURL!=null&&redirectURL!=""){location.href="'.$PaymentURL.'";}</script> ';*/
		
	}
	
	
	//广告
	function _getadstips(){
		$DEDEArchives = D("DEDEArchives");//文章主表
		$tips = $DEDEArchives->where("`typeid` = '73'")->order('id desc')->findall();
		return $tips;
	}
	
}
?>





