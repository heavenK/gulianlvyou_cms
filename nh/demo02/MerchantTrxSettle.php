<html>
<head>
<title>TrustPay - 下载交易对账单</title>
<meta http-equiv='Content-Type' content='text/html; charset=GB2312'>
</head>
<body>
<?php 
	require("api.php");

	$add = "http://www.gulianlvyou.com:8080/axis02/services/B2CWarpper?wsdl";
	//$add = "http://187.61.1.5:8080/axis/services/B2CWarpper?wsdl";
	
	$tSettleDate = $_POST['SettleDate'];
	//$merchantTrxSettle = new MerchantTrxSettle($add,$tSettleDate);
	$merchantTrxSettleRequest = new MerchantTrxSettleRequest($tSettleDate);
	$merchantTrxSettle = new MerchantTrxSettle($add,$merchantTrxSettleRequest);
	$merchantTrxSettleResult = $merchantTrxSettle->invoke();
	//$merchantTrxSettle->showResult();
	//显示结果
	if($merchantTrxSettleResult->isSucess==TRUE)
	{
		print("<br>Sucess!!!"."</br>");
		print "<br>SettleDate:".$merchantTrxSettleResult->merchantTrxSettleDetail->SettleDate."</br>";
		print "<br>SettleType:".$merchantTrxSettleResult->merchantTrxSettleDetail->SettleType."</br>";
		print "<br>NumOfPayments:".$merchantTrxSettleResult->merchantTrxSettleDetail->NumOfPayments."</br>";
		print "<br>SumOfPayAmount:".$merchantTrxSettleResult->merchantTrxSettleDetail->SumOfPayAmount."</br>";
		print "<br>NumOfRefunds:".$merchantTrxSettleResult->merchantTrxSettleDetail->NumOfRefunds."</br>";
		print "<br>SumOfRefundAmount:".$merchantTrxSettleResult->merchantTrxSettleDetail->SumOfRefundAmount."</br>";	
		$count = count($merchantTrxSettleResult->merchantTrxSettleDetail->settleItems);
		print "<br>订单明细：</br>";
		for ($i = 0; $i < $count; $i++) 
		{	
			$item = $merchantTrxSettleResult->merchantTrxSettleDetail->settleItems[$i];
			print "<br>$i:".$item."</br>";
		}
	}
	else
	{
		print("<br>Failed!!!"."</br>");
		print("<br>return code:".$merchantTrxSettleResult->returnCode."</br>"); 
		print("<br>Error Message:".$merchantTrxSettleResult->ErrorMessage."</br>");
	}		

?>
</body>
</html>