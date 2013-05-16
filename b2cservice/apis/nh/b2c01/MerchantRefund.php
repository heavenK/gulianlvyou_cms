<html>
<head>
<title>TrustPay - 退货</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 
	require("api.php");

	$add = "http://www.dlgulian.com:8080/axis/services/B2CWarpper?wsdl";
	//$add = "http://187.61.1.5:8080/axis/services/B2CWarpper?wsdl";
	
	$tTrxAmount = $_POST['TrxAmount'];
	$tOrderNo = $_POST['OrderNo'];
	//$merchantRefund = new MerchantRefund($add,$tOrderNo,$tTrxAmount);
	$merchantRefundRequest = new MerchantRefundRequest($tOrderNo,$tTrxAmount);
	$merchantRefund = new MerchantRefund($add,$merchantRefundRequest);
	$merchantRefundResult = $merchantRefund->invoke();
	//$merchantRefund->showResult();
	//显示结果
	if($merchantRefundResult->isSucess==TRUE)
	{
		print("<br>Sucess!!!"."</br>");
		print "<br>BatchNo:".$merchantRefundResult->merchantRefundResponseDetail->BatchNo."</br>";
		print "<br>HostDate:".$merchantRefundResult->merchantRefundResponseDetail->HostDate."</br>";
		print "<br>HostTime:".$merchantRefundResult->merchantRefundResponseDetail->HostTime."</br>";
		print "<br>OrderNo:".$merchantRefundResult->merchantRefundResponseDetail->OrderNo."</br>";
		print "<br>TrxAmount:".$merchantRefundResult->merchantRefundResponseDetail->TrxAmount."</br>";
		print "<br>TrxType:".$merchantRefundResult->merchantRefundResponseDetail->TrxType."</br>";
		print "<br>VoucherNo:".$merchantRefundResult->merchantRefundResponseDetail->VoucherNo."</br>";
	}
	else
	{
		print("<br>Failed!!!"."</br>");
		print("<br>return code:".$merchantRefundResult->returnCode."</br>"); 
		print("<br>Error Message:".$merchantRefundResult->ErrorMessage."</br>");
	}


?>
</body>
</html>