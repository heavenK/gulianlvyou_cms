<html>
<head>
<title>TrustPay - 支付请求</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
</head>
<body>
<?php 
	require("api.php");

	$add = "http://www.dlgulian.com:8080/axis/services/B2CWarpper?wsdl";
	//$add = "http://187.61.1.5:8080/axis/services/B2CWarpper?wsdl";

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
		print("<br>".$_POST['productname'][$i]."</br>");
		$tOrderItems[]=array($_POST['productid'][$i], $_POST['productname'][$i], $_POST['uniteprice'][$i], $_POST['qty'][$i]);
	}
	
	//$merchantPayment = new MerchantPayment($add,$tOrderNo,$tExpiredDate,$tOrderDesc,$tOrderDate,$tOrderTime,$tOrderAmountStr,$tOrderURL,$tBuyIP,$tProductType,$tPaymentType,$tNotifyType,$tResultNotifyURL,$tMerchantRemarks,$tPaymentLinkType);
/*	var_dump($tOrderNo." ".$tExpiredDate." ".$tOrderDesc." ".$tOrderDate." ".$tOrderTime." ".$tOrderAmountStr." ".$tOrderURL." ".$tBuyIP." ".$tProductType." ".$tPaymentType." ".$tNotifyType." ".$tResultNotifyURL." ".$tMerchantRemarks." ".$tPaymentLinkType." ".$tOrderItems);
	exit;*/
	
	$merchantPaymentRequest = new MerchantPaymentRequest($tOrderNo,$tExpiredDate,$tOrderDesc,$tOrderDate,$tOrderTime,$tOrderAmountStr,$tOrderURL,$tBuyIP,$tProductType,$tPaymentType,$tNotifyType,$tResultNotifyURL,$tMerchantRemarks,$tPaymentLinkType,$tOrderItems);
	$merchantPayment = new MerchantPayment($add,$merchantPaymentRequest);
	$merchantPaymentResult = $merchantPayment->invoke();
	//$merchantPayment->showResult();
	//显示结果
	if($merchantPaymentResult->isSucess==TRUE)
	{
		$PaymentURL = $merchantPaymentResult->paymentURL;
	}
	else
	{
		print("<br>Failed!!!"."</br>");
		print("<br>return code:".$merchantPaymentResult->returnCode."</br>"); 
		print("<br>Error Message:".iconv("GBK","UTF-8",$merchantPaymentResult->ErrorMessage)."</br>");
	}

?>

	<script language=javascript>
	//	支付请求页面跳转
		var redirectURL="<?=$PaymentURL?>";
		if(redirectURL!=null&&redirectURL!="")
		{
			location.href='<?=$PaymentURL?>';
		}
	</script> 
</body>
</html>