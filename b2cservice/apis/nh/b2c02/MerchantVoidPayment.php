<html>
<head>
<title>TrustPay - 取消支付</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 
	require("api.php");

	$add = "http://www.dlgulian.com:8080/axis02/services/B2CWarpper?wsdl";
	//$add = "http://187.61.1.5:8080/axis/services/B2CWarpper?wsdl";
	
	$tOrderNo = $_POST['OrderNo'];
	//$merchantVoidPayment = new MerchantVoidPayment($add,$tOrderNo);
	$merchantVoidPaymentRequest = new MerchantVoidPaymentRequest($tOrderNo);
	$merchantVoidPayment = new MerchantVoidPayment($add,$merchantVoidPaymentRequest);
	$merchantVoidPaymentResult = $merchantVoidPayment->invoke();
	//$merchantVoidPayment->showResult();	
	//显示结果
	if($merchantVoidPaymentResult->isSucess==TRUE)
	{
		print("<br>Sucess!!!"."</br>");
		print "<br>BatchNo:".$merchantVoidPaymentResult->voidPaymentResponseDetail->BatchNo."</br>";
		print "<br>HostDate:".$merchantVoidPaymentResult->voidPaymentResponseDetail->HostDate."</br>";
		print "<br>HostTime:".$merchantVoidPaymentResult->voidPaymentResponseDetail->HostTime."</br>";
		print "<br>OrderNo:".$merchantVoidPaymentResult->voidPaymentResponseDetail->OrderNo."</br>";
		print "<br>PayAmount:".$merchantVoidPaymentResult->voidPaymentResponseDetail->PayAmount."</br>";
		print "<br>TrxType:".$merchantVoidPaymentResult->voidPaymentResponseDetail->TrxType."</br>";
		print "<br>VoucherNo:".$merchantVoidPaymentResult->voidPaymentResponseDetail->VoucherNo."</br>";
	}
	else
	{
		print("<br>Failed!!!"."</br>");
		print("<br>return code:".$merchantVoidPaymentResult->returnCode."</br>"); 
		print("<br>Error Message:".$merchantVoidPaymentResult->ErrorMessage."</br>");
	}


?>
</body>
</html>