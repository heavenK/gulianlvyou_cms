<html>
<head>
<title>TrustPay - 批量退款发送</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 
	require("api.php");

	$add = "http://127.0.0.1:8080/axis/services/B2CWarpper?wsdl";
	//$add = "http://187.61.1.5:8080/axis/services/B2CWarpper?wsdl";

	$tSerialNumber = $_POST['SerialNumber'];
	//$merchantBatchSend = new MerchantBatchSend($add,$tSerialNumber);
	$merchantBatchSendRequest = new MerchantBatchSendRequest($tSerialNumber);
	$merchantBatchSend = new MerchantBatchSend($add,$merchantBatchSendRequest);
	$merchantBatchSendResult = $merchantBatchSend->invoke();	
	//$merchantBatchSend->showResult();
	//显示结果
	if($merchantBatchSendResult->isSucess==TRUE)
	{
		print("<br>Sucess!!!"."</br>");
		print "<br>SendTime:".$merchantBatchSendResult->merchantBatchSendResDetail->SendTime."</br>";
		print "<br>SerialNumber:".$merchantBatchSendResult->merchantBatchSendResDetail->SerialNumber."</br>";
		print "<br>TrxType:".$merchantBatchSendResult->merchantBatchSendResDetail->TrxType."</br>";
	}
	else
	{
		print("<br>Failed!!!"."</br>");
		print("<br>return code:".$merchantBatchSendResult->returnCode."</br>"); 
		print("<br>Error Message:".$merchantBatchSendResult->ErrorMessage."</br>");
	}

?>
</body>
</html>